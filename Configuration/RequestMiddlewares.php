<?php

declare(strict_types=1);

return [
    'frontend' => [
        'erhaweb/klaro-consent-manager/replace-content' => [
            'target' => \ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput::class,
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
