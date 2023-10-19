<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'iconfile' => 'EXT:klaro_consent_manager/Resources/Public/Icons/tx_klaroconsentmanager_service.svg',
        'rootLevel' => -1,
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
        'name' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.description',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'eval' => 'nospace,alphanum_x,required',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.placeholder',
                'valuePicker' => [
                    'items' => [
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.adsense', 'adsense'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.camera', 'camera'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.clarity', 'clarity'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.cloudflare', 'cloudflare'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.external-tracker', 'external-tracker'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.facebook-pixel', 'facebook-pixel'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.google-analytics', 'google-analytics'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.google-fonts', 'google-fonts'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.google-maps', 'google-maps'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.google-tag-manager', 'google-tag-manager'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.inline-tracker', 'inline-tracker'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.intercom', 'intercom'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.matomo', 'matomo'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.mouseflow', 'mouseflow'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.twitter', 'twitter'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.userlike', 'userlike'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.vimeo', 'vimeo'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.youtube', 'youtube'],
                    ],
                ],
            ],
        ],
        'purposes' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'default' => '',
                'minitems' => 1,
                'items' => [
                    // Klaro! Default Purposes
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.functional', 'functional', '', 'default'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.performance', 'performance', '', 'default'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.marketing', 'marketing', '', 'default'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.advertising', 'advertising', '', 'default'],

                    // Additional Purposes
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.analytics', 'analytics', '', 'additional'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.security', 'security', '', 'additional'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.livechat', 'livechat', '', 'additional'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.styling', 'styling', '', 'additional'],
                ],
                'itemGroups' => [
                    'custom' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.optgroup.custom',
                    'additional' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.optgroup.additional',
                    'default' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.optgroup.default',
                ],
            ],
        ],
        'default' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.default.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.default.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ]
                ],
            ]
        ],
        'required' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.required.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.required.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ]
                ],
            ]
        ],
        'opt_out' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.opt_out.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.opt_out.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ]
                ],
            ]
        ],
        'only_once' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.only_once.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.only_once.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ]
                ],
            ]
        ],
        'cookies' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.cookies.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.cookies.description',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_klaroconsentmanager_cookie',
                'foreign_field' => 'parentid',
                'foreign_table_field' => 'parenttable',
                'appearance' => [
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                ],
            ],
        ],
        'callback' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.callback.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.callback.description',
            'config' => [
                'type' => 'text',
                'renderType' => 't3editor',
                'format' => 'javascript',
                'rows' => 10,
            ]
        ],
        'contextual_consent_only' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.contextual_consent_only.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.contextual_consent_only.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ]
                ],
            ]
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
                --div--;Toggles,
                    --palette--;;toggles_palette,
                --div--;Advanced,
                    --palette--;;advanced_palette,
                --div--;Access,
                    --palette--;;access
            '
        ],
    ],
    'palettes' => [
        'general_palette' => [
            'showitem' => 'name, default, --linebreak--, purposes, --linebreak--, cookies',
        ],
        'toggles_palette' => [
            'showitem' => 'required, opt_out, --linebreak--, only_once, contextual_consent_only'
        ],
        'advanced_palette' => [
            'showitem' => 'callback',
        ],
        'access' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];