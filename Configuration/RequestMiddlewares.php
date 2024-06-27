<?php declare(strict_types=1);

use ErHaWeb\KlaroConsentManager\Middleware\KlaroConfiguration;
use ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput;

return [
    'frontend' => [
        'erhaweb/klaro-consent-manager/klaro-configuration' => [
            'target' => KlaroConfiguration::class,
            'before' => [
                'typo3/cms-frontend/eid',
            ],
            'after' => [
                'typo3/cms-frontend/site'
            ],
        ],
        'erhaweb/klaro-consent-manager/replace-content' => [
            'target' => ReplaceBeforeOutput::class,
            'before' => [
                'typo3/cms-frontend/output-compression',
                'typo3/cms-frontend/content-length-headers',
            ],
            'after' => [
                'typo3/cms-frontend/tsfe',
            ],
        ],
    ],
];
