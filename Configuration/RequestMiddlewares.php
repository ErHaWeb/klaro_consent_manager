<?php

declare(strict_types=1);

use ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput;

return [
    'frontend' => [
        'erhaweb/klaro-consent-manager/replace-content' => [
            'target' => ReplaceBeforeOutput::class,
            // Ensure Klaro modifies the final HTML before Content-Length is calculated.
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
                'typo3/cms-frontend/content-length-headers',
            ],
        ],
    ],
];
