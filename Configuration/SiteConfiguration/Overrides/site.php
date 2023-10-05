<?php

// Experimental example to add a new field to the site configuration

\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
    $GLOBALS['SiteConfiguration']['site'],
    [
        'columns' => [
            'klaroConfiguration' => [
                'label' => 'Klaro! Configuration',
                'description' => 'Klaro configurations created in the root of this TYPO3 instance can be referenced here. If no configuration has been created yet, switch to the list module, select the root node of the page tree (with uid 0) and create a new configuration via the button "Create new record".',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'selectSingle',
                    'foreign_table' => 'tx_klaroconsentmanager_configuration',
                    'items' => [
                        ['None', 0, ''],
                    ],
                ],
            ],
            'klaroPrivacyPolicyUrl' => [
                'label' => 'Privacy Policy URL',
                'description' => 'The link to the privacy policy page is used in the introductory text of the Klaro! Consent Box.',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputLink',
                    'allowedTypes' => ['page', 'url', 'record'],
                ],
            ],
            'klaroImprintUrl' => [
                'label' => 'Imprint URL',
                'description' => 'The link to the imprint page is used in the footer of the Klaro! Consent Box.',
                'config' => [
                    'type' => 'input',
                    'renderType' => 'inputLink',
                    'allowedTypes' => ['page', 'url', 'record'],
                ],
            ],
        ],
        'palettes' => [
            'klaro' => [
                'showitem' => 'klaroConfiguration, --linebreak--, klaroPrivacyPolicyUrl, klaroImprintUrl'
            ],
        ],
    ],
);


$GLOBALS['SiteConfiguration']['site']['types']['0']['showitem'] .= ',
    --div--;Klaro!,
        --palette--;;klaro
';
