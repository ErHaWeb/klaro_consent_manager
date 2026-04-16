#!/usr/bin/env bash

set -euo pipefail

readonly VERSION="${1:-}"
readonly OUTPUT_FILE="${2:-}"
readonly RELEASE_PATH="${3:-.}"
readonly OUTPUT_FORMAT="${4:-github_env}"

if [[ -z "${VERSION}" ]]; then
    echo "Usage: $0 <version> [output-file] [release-path] [output-format]" >&2
    exit 1
fi

if ! [[ "${VERSION}" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo "Version '${VERSION}' must match x.y.z." >&2
    exit 1
fi

if [[ ! -d "${RELEASE_PATH}" ]]; then
    echo "Release path '${RELEASE_PATH}' does not exist." >&2
    exit 1
fi

if [[ "${OUTPUT_FORMAT}" != "github_env" && "${OUTPUT_FORMAT}" != "shell" ]]; then
    echo "Output format '${OUTPUT_FORMAT}' is invalid. Use github_env or shell." >&2
    exit 1
fi

cd "${RELEASE_PATH}"

if ! git rev-parse --verify --quiet "refs/tags/${VERSION}^{commit}" >/dev/null; then
    echo "Tag '${VERSION}' does not exist." >&2
    exit 1
fi

TAG_COMMIT="$(git rev-parse "refs/tags/${VERSION}^{commit}")"
readonly TAG_COMMIT
CURRENT_COMMIT="$(git rev-parse HEAD)"
readonly CURRENT_COMMIT

if [[ "${CURRENT_COMMIT}" != "${TAG_COMMIT}" ]]; then
    echo "Current checkout must match tag '${VERSION}'." >&2
    exit 1
fi

EXTENSION_KEY="${TYPO3_EXTENSION_KEY:-}"

if [[ -z "${EXTENSION_KEY}" && -f "composer.json" ]]; then
    # shellcheck disable=SC2016
    EXTENSION_KEY="$(
        php -r '
        $composer = json_decode(file_get_contents("composer.json"), true, 512, JSON_THROW_ON_ERROR);
        echo $composer["extra"]["typo3/cms"]["extension-key"] ?? "";
        '
    )"
fi

if [[ -z "${EXTENSION_KEY}" && -f "ext_emconf.php" ]]; then
    # shellcheck disable=SC2016
    EXTENSION_KEY="$(
        php -r '
        $_EXTKEY = "__probe__";
        $EM_CONF = [];
        require "ext_emconf.php";
        if (isset($EM_CONF[$_EXTKEY])) {
            echo $_EXTKEY;
            return;
        }
        echo count($EM_CONF) === 1 ? (string)array_key_first($EM_CONF) : "";
        '
    )"
fi

readonly EXTENSION_KEY

if [[ -z "${EXTENSION_KEY}" ]]; then
    echo "Unable to determine TYPO3 extension key." >&2
    exit 1
fi

# shellcheck disable=SC2016
EXT_EMCONF_VERSION="$(
    php -r '
    $extensionKey = $argv[1];
    $_EXTKEY = $extensionKey;
    $EM_CONF = [];
    require "ext_emconf.php";
    echo $EM_CONF[$extensionKey]["version"] ?? "";
    ' "${EXTENSION_KEY}"
)"
readonly EXT_EMCONF_VERSION

if [[ "${EXT_EMCONF_VERSION}" != "${VERSION}" ]]; then
    echo "ext_emconf.php version '${EXT_EMCONF_VERSION}' does not match tag '${VERSION}'." >&2
    exit 1
fi

if [[ -f "Documentation/Settings.cfg" ]]; then
    SETTINGS_VERSION="$(
        sed -nE 's/^version[[:space:]]*=[[:space:]]*([0-9]+\.[0-9]+\.[0-9]+)$/\1/p' Documentation/Settings.cfg
    )"
    readonly SETTINGS_VERSION

    if [[ "${SETTINGS_VERSION}" != "${VERSION}" ]]; then
        echo "Documentation/Settings.cfg version '${SETTINGS_VERSION}' does not match tag '${VERSION}'." >&2
        exit 1
    fi
fi

if [[ -f "Documentation/guides.xml" ]]; then
    GUIDES_VERSION="$(
        sed -nE 's|.*<project[^>]* version="([0-9]+\.[0-9]+\.[0-9]+)".*|\1|p' Documentation/guides.xml
    )"
    readonly GUIDES_VERSION

    if [[ "${GUIDES_VERSION}" != "${VERSION}" ]]; then
        echo "Documentation/guides.xml version '${GUIDES_VERSION}' does not match tag '${VERSION}'." >&2
        exit 1
    fi
fi

# shellcheck disable=SC2016
LICENSE_NAME="$(
    php -r '
    $composer = json_decode(file_get_contents("composer.json"), true, 512, JSON_THROW_ON_ERROR);
    $license = $composer["license"] ?? "";
    if (is_array($license)) {
        echo in_array("GPL-2.0-or-later", $license, true) ? "GPL-2.0-or-later" : implode(",", $license);
        return;
    }
    echo (string)$license;
    '
)"
readonly LICENSE_NAME

if [[ "${LICENSE_NAME}" != "GPL-2.0-or-later" ]]; then
    echo "composer.json license must be GPL-2.0-or-later." >&2
    exit 1
fi

license_found=false
for candidate in LICENSE LICENSE.md LICENSE.txt COPYING; do
    if [[ -f "${candidate}" ]]; then
        license_found=true
        break
    fi
done

if [[ "${license_found}" != "true" ]]; then
    echo "A license file is missing." >&2
    exit 1
fi

TAG_HAS_PARENT=false
if git rev-parse --verify --quiet "${TAG_COMMIT}^" >/dev/null; then
    TAG_HAS_PARENT=true
    RANGE_END="$(git rev-parse "${TAG_COMMIT}^")"
else
    RANGE_END="${TAG_COMMIT}"
fi
readonly RANGE_END

PREVIOUS_TAG=""
if [[ "${TAG_HAS_PARENT}" == "true" ]] \
    && PREVIOUS_TAG="$(git describe --tags --abbrev=0 --match '[0-9]*.[0-9]*.[0-9]*' "${RANGE_END}" 2>/dev/null)"; then
    COMMENT="$(git log --no-merges --pretty=format:%s "${PREVIOUS_TAG}..${RANGE_END}")"
else
    COMMENT="$(git log --no-merges --pretty=format:%s "${RANGE_END}")"
    PREVIOUS_TAG=""
fi

COMMENT="$(printf '%s\n' "${COMMENT}" | sed '/^[[:space:]]*$/d')"
readonly COMMENT

if [[ -z "${COMMENT}" ]]; then
    echo "Release comment would be empty for tag '${VERSION}'." >&2
    exit 1
fi

if [[ -n "${OUTPUT_FILE}" ]]; then
    if [[ "${OUTPUT_FORMAT}" == "github_env" ]]; then
        {
            printf 'version=%s\n' "${VERSION}"
            printf 'previous_tag=%s\n' "${PREVIOUS_TAG}"
            printf 'comment<<__TER_COMMENT__\n%s\n__TER_COMMENT__\n' "${COMMENT}"
        } >> "${OUTPUT_FILE}"
    else
        {
            printf 'version=%q\n' "${VERSION}"
            printf 'previous_tag=%q\n' "${PREVIOUS_TAG}"
            printf "comment=\$(cat <<'__TER_COMMENT__'\n%s\n__TER_COMMENT__\n)\n" "${COMMENT}"
            printf 'export version previous_tag comment\n'
        } >> "${OUTPUT_FILE}"
    fi
fi

printf 'Prepared TER release metadata for %s\n' "${VERSION}"
printf 'Extension key: %s\n' "${EXTENSION_KEY}"
if [[ -n "${PREVIOUS_TAG}" ]]; then
    printf 'Previous tag: %s\n' "${PREVIOUS_TAG}"
else
    printf 'Previous tag: <none>\n'
fi
