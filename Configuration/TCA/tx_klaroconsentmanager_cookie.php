<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_cookie',
        'label' => 'title',
        'label_alt' => 'pattern',
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
                --div--;General,
                    --palette--;;general_palette,
                --div--;Access,
                    --palette--;;access
            '
        ],
    ],
    'palettes' => [
        'general_palette' => [
            'showitem' => 'title, pattern,  path, --linebreak--, domain, expires_after, expires_after_unit'
        ],
        'access' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];