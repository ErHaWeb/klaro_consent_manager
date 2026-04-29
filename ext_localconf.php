<?php

declare(strict_types=1);

defined('TYPO3') || die();

(static function (): void {
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['klaro'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['klaro'] = [];
    }

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['klaro'][] =
        'ErHaWeb\\KlaroConsentManager\\ViewHelpers';
})();
