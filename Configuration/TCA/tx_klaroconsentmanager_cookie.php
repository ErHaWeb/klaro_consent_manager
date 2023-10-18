<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie',
        'label' => 'pattern',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'iconfile' => 'EXT:klaro_consent_manager/Resources/Public/Icons/Extension.svg',
        'rootLevel' => 1,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'hideTable' => true,
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.hidden_toggle',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ]
        ],
        'pattern' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.pattern.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.pattern.description',
            'config' => [
                'type' => 'input',
                'eval' => 'required',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.pattern.placeholder',
            ],
        ],
        'path' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.path.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.path.description',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.path.placeholder',
            ],
        ],
        'domain' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.domain.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.domain.description',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.domain.placeholder',
            ],
        ],
        'parentid' => [
            'label' => '',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
        'parenttable' => [
            'label' => '',
            'config' => [
                'type' => 'passthrough'
            ]
        ],
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;General,
                    --palette--;;general_palette,
                --div--;Access,
                    --palette--;;access
            '
        ],
    ],
    'palettes' => [
        'general_palette' => [
            'showitem' => 'pattern, --linebreak--, path, --linebreak--, domain'
        ],
        'access' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];
