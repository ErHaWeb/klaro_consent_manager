#!/usr/bin/env bash

#
# Package-local TYPO3 extension test runner for klaro_consent_manager.
#

set -eu -o pipefail

COMPOSER_FILES_STASHED=0

cleanUp() {
    if [ -n "${CONTAINER_BIN:-}" ] && [ "${CONTAINER_BIN}" != "host" ] && [ -n "${NETWORK:-}" ]; then
        ATTACHED_CONTAINERS=$(${CONTAINER_BIN} ps -a --filter network="${NETWORK}" --format='{{.Names}}' 2>/dev/null || true)
        for ATTACHED_CONTAINER in ${ATTACHED_CONTAINERS}; do
            ${CONTAINER_BIN} rm -f "${ATTACHED_CONTAINER}" >/dev/null 2>&1 || true
        done
        ${CONTAINER_BIN} network rm "${NETWORK}" >/dev/null 2>&1 || true
    fi
}

onExit() {
    local EXIT_CODE=$?
    trap - EXIT

    if [ "${COMPOSER_FILES_STASHED:-0}" -eq 1 ]; then
        restoreComposerFiles || true
    fi

    cleanUp || true
    exit "${EXIT_CODE}"
}

trap onExit EXIT
trap 'exit 2' INT TERM

loadHelp() {
    cat <<EOF
Package-local TYPO3 extension test runner for klaro_consent_manager.

Usage: Build/Scripts/runTests.sh [options] [-- extra-args]

Options:
    -s <composer|composerInstall|composerValidate|unit|functional|rector|fractor|clean>
        Specifies which suite to run

    -t <13|14>
        TYPO3 major to use for composerInstall
            - 13: use TYPO3 13.4
            - 14: use TYPO3 14.3 (default)

    -p <8.2|8.3|8.4|8.5>
        PHP minor version to use
            - 8.2 (default)
            - 8.3
            - 8.4
            - 8.5

    -d <sqlite|mariadb|mysql|postgres>
        Only with -s functional
        Database engine for functional test suites
            - sqlite (default)
            - mariadb
            - mysql
            - postgres

    -a <mysqli|pdo_mysql>
        Only with -s functional and -d mariadb|mysql

    -i <version>
        Optional database version for mariadb/mysql/postgres
        Only with -s functional

    -b <host|docker|podman>
        Execution backend

    -e "<tool options>"
        Pass-through options for the underlying tool or test runner

    -n
        Only with -s rector|fractor
        Activate dry-run mode

    -x
        Enable xdebug

    -y <port>
        Xdebug port, default 9003

    -h
        Show this help

Examples:
    Build/Scripts/runTests.sh -s composerValidate
    Build/Scripts/runTests.sh -s composerInstall
    Build/Scripts/runTests.sh -s composerInstall -t 13 -p 8.4
    Build/Scripts/runTests.sh -s unit
    Build/Scripts/runTests.sh -s functional
    Build/Scripts/runTests.sh -s functional -d postgres
    Build/Scripts/runTests.sh -s rector -n
    Build/Scripts/runTests.sh -s fractor -n
EOF
}

handleDbmsOptions() {
    case ${DBMS} in
        mariadb)
            if [ -z "${DATABASE_DRIVER}" ]; then
                DATABASE_DRIVER="mysqli"
            fi
            if [ "${DATABASE_DRIVER}" != "mysqli" ] && [ "${DATABASE_DRIVER}" != "pdo_mysql" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                exit 1
            fi
            if [ -z "${DBMS_VERSION}" ]; then
                DBMS_VERSION="10.11"
            fi
            ;;
        mysql)
            if [ -z "${DATABASE_DRIVER}" ]; then
                DATABASE_DRIVER="mysqli"
            fi
            if [ "${DATABASE_DRIVER}" != "mysqli" ] && [ "${DATABASE_DRIVER}" != "pdo_mysql" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                exit 1
            fi
            if [ -z "${DBMS_VERSION}" ]; then
                DBMS_VERSION="8.0"
            fi
            ;;
        postgres)
            if [ -n "${DATABASE_DRIVER}" ]; then
                echo "Invalid combination -d ${DBMS} -a ${DATABASE_DRIVER}" >&2
                exit 1
            fi
            if [ -z "${DBMS_VERSION}" ]; then
                DBMS_VERSION="16"
            fi
            ;;
        sqlite)
            if [ -n "${DATABASE_DRIVER}" ] || [ -n "${DBMS_VERSION}" ]; then
                echo "Invalid sqlite combination" >&2
                exit 1
            fi
            ;;
        *)
            echo "Invalid option -d ${DBMS}" >&2
            exit 1
            ;;
    esac
}

cleanComposer() {
    rm -rf .Build composer.lock
}

stashComposerFiles() {
    cp composer.json composer.json.orig
}

restoreComposerFiles() {
    if [ -f composer.json.orig ]; then
        mv composer.json.orig composer.json
    fi
}

waitFor() {
    local HOST=${1}
    local PORT=${2}
    local CONTAINER_NAME=${3}
    local TESTCOMMAND="
        COUNT=0;
        while ! nc -z ${HOST} ${PORT}; do
            if [ \"\${COUNT}\" -gt 60 ]; then
                echo \"Can not connect to ${HOST} port ${PORT}. Aborting.\" >&2;
                exit 1;
            fi;
            sleep 1;
            COUNT=\$((COUNT + 1));
        done;
    "

    echo "Waiting for ${HOST}:${PORT} ..."
    ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name "${CONTAINER_NAME}" ${IMAGE_PHP} /bin/sh -c "${TESTCOMMAND}"
}

logContainerLogs() {
    local CONTAINER_NAME=${1}

    if [ -z "${CONTAINER_BIN:-}" ] || [ "${CONTAINER_BIN}" = "host" ]; then
        return
    fi

    echo "---- Logs for ${CONTAINER_NAME} ----" >&2
    ${CONTAINER_BIN} logs "${CONTAINER_NAME}" >&2 || true
    echo "---- End logs for ${CONTAINER_NAME} ----" >&2
}

startDbContainer() {
    local CONTAINER_NAME=${1}
    shift

    echo "Starting database container ${CONTAINER_NAME} ..."
    if ! ${CONTAINER_BIN} run --rm --name "${CONTAINER_NAME}" --network "${NETWORK}" -d "$@" >/dev/null; then
        echo "Could not start database container ${CONTAINER_NAME}" >&2
        logContainerLogs "${CONTAINER_NAME}"
        exit 1
    fi
}

waitForOrDumpLogs() {
    local HOST=${1}
    local PORT=${2}
    local WAIT_CONTAINER_NAME=${3}
    local DB_CONTAINER_NAME=${4}

    if ! waitFor "${HOST}" "${PORT}" "${WAIT_CONTAINER_NAME}"; then
        logContainerLogs "${DB_CONTAINER_NAME}"
        exit 1
    fi
}

createContainerNetwork() {
    if ${CONTAINER_BIN} network create "${NETWORK}"; then
        return 0
    fi

    echo "Could not create container network ${NETWORK}" >&2
    return 1
}

runPhpCommand() {
    local CONTAINER_NAME=${1}
    shift
    local COMMAND=("$@")

    if [ "${CONTAINER_BIN}" = "host" ]; then
        COMPOSER_ROOT_VERSION="${COMPOSER_ROOT_VERSION}" "${COMMAND[@]}"
        return
    fi

    ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name "${CONTAINER_NAME}" ${XDEBUG_MODE} \
        -e COMPOSER_ROOT_VERSION="${COMPOSER_ROOT_VERSION}" \
        -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" \
        ${EXTRA_ENV_PARAMS:-} \
        ${IMAGE_PHP} "${COMMAND[@]}"
}

runPhpShellCommand() {
    local CONTAINER_NAME=${1}
    local COMMAND=${2}

    if [ "${CONTAINER_BIN}" = "host" ]; then
        local ENV_EXPORTS="export COMPOSER_ROOT_VERSION='${COMPOSER_ROOT_VERSION}';"
        if [ ${PHP_XDEBUG_ON} -eq 0 ]; then
            ENV_EXPORTS="${ENV_EXPORTS} export XDEBUG_MODE=off;"
        else
            ENV_EXPORTS="${ENV_EXPORTS} export XDEBUG_MODE=debug XDEBUG_TRIGGER=klaro_consent_manager XDEBUG_CONFIG=client_port=${PHP_XDEBUG_PORT};"
        fi
        if [ -n "${EXTRA_ENV_PARAMS:-}" ]; then
            ENV_EXPORTS="${ENV_EXPORTS} export ${EXTRA_ENV_PARAMS};"
        fi
        bash -lc "${ENV_EXPORTS} ${COMMAND}"
        return
    fi

    ${CONTAINER_BIN} run ${CONTAINER_COMMON_PARAMS} --name "${CONTAINER_NAME}" ${XDEBUG_MODE} \
        -e COMPOSER_ROOT_VERSION="${COMPOSER_ROOT_VERSION}" \
        -e XDEBUG_CONFIG="${XDEBUG_CONFIG}" \
        ${EXTRA_ENV_PARAMS:-} \
        ${IMAGE_PHP} /bin/sh -lc "${COMMAND}"
}

runComposerInstallInWorkingDir() {
    local WORKING_DIR=${1}

    if [ ! -f "${WORKING_DIR}/composer.json" ]; then
        echo "Missing composer.json in ${WORKING_DIR}" >&2
        exit 1
    fi

    runPhpShellCommand \
        "composer-install-$(basename "${WORKING_DIR}")-${SUFFIX}" \
        "composer --working-dir=${WORKING_DIR} install --no-ansi --no-interaction --no-progress"
}

THIS_SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" >/dev/null && pwd)"
cd "${THIS_SCRIPT_DIR}/../../" || exit 1
ROOT_DIR="${PWD}"

TEST_SUITE=""
TYPO3_VERSION="14"
DBMS="sqlite"
DBMS_VERSION=""
DATABASE_DRIVER=""
PHP_VERSION="8.2"
PHP_XDEBUG_ON=0
PHP_XDEBUG_PORT=9003
EXTRA_TEST_OPTIONS=""
DRY_RUN=0
CONTAINER_BIN=""
CONTAINER_INTERACTIVE="--init"
HOST_UID=$(id -u)
HOST_GID=$(id -g)
SUFFIX="${RANDOM}-$$"
NETWORK="klaro-consent-manager-${SUFFIX}"
CONTAINER_HOST="host.docker.internal"
USERSET=""

while getopts "a:b:s:d:i:p:e:t:nxy:h" OPT; do
    case ${OPT} in
        s) TEST_SUITE=${OPTARG} ;;
        a) DATABASE_DRIVER=${OPTARG} ;;
        b) CONTAINER_BIN=${OPTARG} ;;
        d) DBMS=${OPTARG} ;;
        i) DBMS_VERSION=${OPTARG} ;;
        p) PHP_VERSION=${OPTARG} ;;
        e) EXTRA_TEST_OPTIONS=${OPTARG} ;;
        t) TYPO3_VERSION=${OPTARG} ;;
        n) DRY_RUN=1 ;;
        x) PHP_XDEBUG_ON=1 ;;
        y) PHP_XDEBUG_PORT=${OPTARG} ;;
        h)
            loadHelp
            exit 0
            ;;
        *)
            loadHelp >&2
            exit 1
            ;;
    esac
done
shift $((OPTIND - 1))

if ! [[ ${TYPO3_VERSION} =~ ^(13|14)$ ]]; then
    echo "Unsupported TYPO3 major: ${TYPO3_VERSION}" >&2
    exit 1
fi

if ! [[ ${PHP_VERSION} =~ ^(8.2|8.3|8.4|8.5)$ ]]; then
    echo "Unsupported PHP version: ${PHP_VERSION}" >&2
    exit 1
fi

if [ -n "${CONTAINER_BIN}" ] && ! [[ ${CONTAINER_BIN} =~ ^(host|docker|podman)$ ]]; then
    echo "Unsupported container backend: ${CONTAINER_BIN}" >&2
    exit 1
fi

case ${TEST_SUITE} in
    clean)
        rm -rf .Build .cache composer.lock composer.json.orig composer.json.testing .phpunit.cache
        exit 0
        ;;
esac

handleDbmsOptions

if [ -z "${CONTAINER_BIN}" ]; then
    if command -v podman >/dev/null 2>&1 && podman info >/dev/null 2>&1; then
        CONTAINER_BIN="podman"
    elif command -v docker >/dev/null 2>&1; then
        CONTAINER_BIN="docker"
    else
        echo "This script relies on docker or podman unless -b host is used explicitly" >&2
        exit 1
    fi
elif [ "${CONTAINER_BIN}" != "host" ] && ! command -v "${CONTAINER_BIN}" >/dev/null 2>&1; then
    echo "Requested container backend not found: ${CONTAINER_BIN}" >&2
    exit 1
fi

if [ "$(uname)" != "Darwin" ] && [ "${CONTAINER_BIN}" = "docker" ]; then
    USERSET="--user ${HOST_UID}:${HOST_GID}"
fi

if [ -t 0 ] && [ -t 1 ]; then
    CONTAINER_INTERACTIVE="-it --init"
fi

mkdir -p .cache .Build/Web/typo3temp/var/tests .Build/coverage

IMAGE_PHP="ghcr.io/typo3/core-testing-php${PHP_VERSION//./}:latest"
IMAGE_MARIADB="docker.io/mariadb:${DBMS_VERSION}"
IMAGE_MYSQL="docker.io/mysql:${DBMS_VERSION}"
IMAGE_POSTGRES="docker.io/postgres:${DBMS_VERSION}-alpine"
COMPOSER_ROOT_VERSION="${TYPO3_VERSION}.0.0-dev"

if [ "${CONTAINER_BIN}" != "host" ]; then
    createContainerNetwork

    if [ "${CONTAINER_BIN}" = "docker" ]; then
        CONTAINER_COMMON_PARAMS="${CONTAINER_INTERACTIVE} --rm --network ${NETWORK} --add-host ${CONTAINER_HOST}:host-gateway ${USERSET} -v ${ROOT_DIR}:${ROOT_DIR} -w ${ROOT_DIR}"
    else
        CONTAINER_HOST="host.containers.internal"
        CONTAINER_COMMON_PARAMS="${CONTAINER_INTERACTIVE} --rm --network ${NETWORK} -v ${ROOT_DIR}:${ROOT_DIR} -w ${ROOT_DIR}"
    fi
fi

if [ ${PHP_XDEBUG_ON} -eq 0 ]; then
    XDEBUG_MODE="-e XDEBUG_MODE=off"
    XDEBUG_CONFIG="client_host=${CONTAINER_HOST} client_port=${PHP_XDEBUG_PORT}"
else
    XDEBUG_MODE="-e XDEBUG_MODE=debug -e XDEBUG_TRIGGER=klaro_consent_manager"
    XDEBUG_CONFIG="client_host=${CONTAINER_HOST} client_port=${PHP_XDEBUG_PORT}"
fi

EXTRA_ENV_PARAMS=""

case ${TEST_SUITE} in
    composer)
        runPhpCommand "composer-command-${SUFFIX}" composer "$@"
        ;;
    composerValidate)
        runPhpCommand "composer-validate-${SUFFIX}" composer validate --no-check-lock "$@"
        ;;
    composerInstall)
        cleanComposer
        stashComposerFiles
        COMPOSER_FILES_STASHED=1
        case ${TYPO3_VERSION} in
            13)
                TYPO3_REQUIREMENTS=(
                    typo3/cms-backend:^13.4
                    typo3/cms-core:^13.4
                    typo3/cms-extbase:^13.4
                    typo3/cms-fluid:^13.4
                    typo3/cms-frontend:^13.4
                )
                TYPO3_DEV_REQUIREMENTS=(
                    typo3/cms-fluid-styled-content:^13.4
                    typo3/cms-install:^13.4
                    typo3/testing-framework:^9.0
                    phpunit/phpunit:^11.5
                )
                ;;
            14)
                TYPO3_REQUIREMENTS=(
                    typo3/cms-backend:^14.3
                    typo3/cms-core:^14.3
                    typo3/cms-extbase:^14.3
                    typo3/cms-fluid:^14.3
                    typo3/cms-frontend:^14.3
                )
                TYPO3_DEV_REQUIREMENTS=(
                    typo3/cms-fluid-styled-content:^14.3
                    typo3/cms-install:^14.3
                    typo3/testing-framework:^9.0
                    phpunit/phpunit:^11.5
                )
                ;;
        esac
        runPhpCommand "composer-require-${SUFFIX}" composer require --no-ansi --no-interaction --no-progress --no-update "${TYPO3_REQUIREMENTS[@]}"
        runPhpCommand "composer-require-dev-${SUFFIX}" composer require --dev --no-ansi --no-interaction --no-progress --no-update "${TYPO3_DEV_REQUIREMENTS[@]}"
        runPhpCommand "composer-install-${SUFFIX}" composer install --no-ansi --no-interaction --no-progress "$@"
        cp composer.json composer.json.testing
        restoreComposerFiles
        COMPOSER_FILES_STASHED=0
        ;;
    unit)
        runPhpShellCommand "unit-${SUFFIX}" ".Build/bin/phpunit -c Build/phpunit/UnitTests.xml ${EXTRA_TEST_OPTIONS} $*"
        ;;
    functional)
        if [ "${CONTAINER_BIN}" = "host" ] && [ "${DBMS}" != "sqlite" ]; then
            echo "Host backend supports only sqlite for functional tests" >&2
            exit 1
        fi
        case ${DBMS} in
            mariadb)
                startDbContainer "mariadb-func-${SUFFIX}" -e MYSQL_ROOT_PASSWORD=funcp --tmpfs /var/lib/mysql/:rw,noexec,nosuid ${IMAGE_MARIADB}
                waitForOrDumpLogs "mariadb-func-${SUFFIX}" 3306 "wait-mariadb-${SUFFIX}" "mariadb-func-${SUFFIX}"
                EXTRA_ENV_PARAMS="-e typo3DatabaseDriver=${DATABASE_DRIVER} -e typo3DatabaseName=func_test -e typo3DatabaseUsername=root -e typo3DatabaseHost=mariadb-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ;;
            mysql)
                startDbContainer "mysql-func-${SUFFIX}" -e MYSQL_ROOT_PASSWORD=funcp --tmpfs /var/lib/mysql/:rw,noexec,nosuid ${IMAGE_MYSQL}
                waitForOrDumpLogs "mysql-func-${SUFFIX}" 3306 "wait-mysql-${SUFFIX}" "mysql-func-${SUFFIX}"
                EXTRA_ENV_PARAMS="-e typo3DatabaseDriver=${DATABASE_DRIVER} -e typo3DatabaseName=func_test -e typo3DatabaseUsername=root -e typo3DatabaseHost=mysql-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ;;
            postgres)
                startDbContainer "postgres-func-${SUFFIX}" -e POSTGRES_PASSWORD=funcp -e POSTGRES_USER=funcu --tmpfs /var/lib/postgresql/data:rw,noexec,nosuid ${IMAGE_POSTGRES}
                waitForOrDumpLogs "postgres-func-${SUFFIX}" 5432 "wait-postgres-${SUFFIX}" "postgres-func-${SUFFIX}"
                EXTRA_ENV_PARAMS="-e typo3DatabaseDriver=pdo_pgsql -e typo3DatabaseName=func_test -e typo3DatabaseUsername=funcu -e typo3DatabaseHost=postgres-func-${SUFFIX} -e typo3DatabasePassword=funcp"
                ;;
            sqlite)
                mkdir -p "${ROOT_DIR}/.Build/Web/typo3temp/var/tests/functional-sqlite-dbs"
                if [ "${CONTAINER_BIN}" = "host" ]; then
                    EXTRA_ENV_PARAMS="typo3DatabaseDriver=pdo_sqlite"
                else
                    EXTRA_ENV_PARAMS="-e typo3DatabaseDriver=pdo_sqlite"
                fi
                ;;
        esac
        runPhpShellCommand "functional-${SUFFIX}" ".Build/bin/phpunit -c Build/phpunit/FunctionalTests.xml ${EXTRA_TEST_OPTIONS} $*"
        ;;
    rector)
        RECTOR_DRY_RUN=""
        [ ${DRY_RUN} -eq 1 ] && RECTOR_DRY_RUN="--dry-run"

        runComposerInstallInWorkingDir "Build/rector"
        runPhpShellCommand "rector-${SUFFIX}" ".Build/rector/bin/rector process --config=Build/rector/rector.php --no-progress-bar --ansi ${RECTOR_DRY_RUN} ${EXTRA_TEST_OPTIONS} $*"
        ;;
    fractor)
        FRACTOR_DRY_RUN=""
        [ ${DRY_RUN} -eq 1 ] && FRACTOR_DRY_RUN="--dry-run"

        runComposerInstallInWorkingDir "Build/fractor"
        runPhpShellCommand "fractor-${SUFFIX}" ".Build/fractor/bin/fractor process --config=Build/fractor/fractor.php --ansi ${FRACTOR_DRY_RUN} ${EXTRA_TEST_OPTIONS} $*"
        ;;
    *)
        loadHelp >&2
        exit 1
        ;;
esac
