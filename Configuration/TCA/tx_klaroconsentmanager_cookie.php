<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie',
        'label' => 'title',
        'label_alt' => 'identifier, pattern',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'iconfile' => 'EXT:klaro_consent_manager/Resources/Public/Icons/tx_klaroconsentmanager_cookie.svg',
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
        'identifier' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.identifier.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.identifier.description',
            'config' => [
                'type' => 'input',
                'eval' => 'nospace,lower,alphanum_x,unique',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.identifier.placeholder',
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.title.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.title.description',
            'config' => [
                'type' => 'input',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.title.placeholder',
            ],
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
        'expires_after' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,num',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after.placeholder',
            ],
        ],
        'expires_after_unit' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'days',
                'items' => [
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.years', 'years'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.months', 'months'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.days', 'days'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.hours', 'hours'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.minutes', 'minutes'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.expires_after_unit.I.seconds', 'seconds'],
                ]
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
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.tabs.general,
                    --palette--;;identification_palette,
                    --palette--;;technic_palette,
                    --palette--;;expiration_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.tabs.access,
                    --palette--;;access_palette
            '
        ],
    ],
    'palettes' => [
        'identification_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.palettes.identification_palette',
            'showitem' => '
                identifier, title
            '
        ],
        'technic_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.palettes.technic_palette',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.palettes.technic_palette.description',
            'showitem' => '
                pattern, path, domain
            '
        ],
        'expiration_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie.palettes.expiration_palette',
            'showitem' => '
                expires_after, expires_after_unit
            '
        ],
        'access_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];