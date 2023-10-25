<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY title',
        'delete' => 'deleted',
        'iconfile' => 'EXT:klaro_consent_manager/Resources/Public/Icons/Extension.png',
        'rootLevel' => -1,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
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
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.title.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.title.description',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'eval' => 'required',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.title.placeholder',
            ],
        ],
        'testing' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.testing.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.testing.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'config_variable_name' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.config_variable_name.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.config_variable_name.description',
            'config' => [
                'type' => 'input',
                'eval' => 'nospace,alphanum',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.config_variable_name.placeholder',
            ]
        ],
        'element_i_d' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.element_i_d.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.element_i_d.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.element_i_d.placeholder',
            ]
        ],
        'no_auto_load' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.no_auto_load.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.no_auto_load.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'additional_class' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.additional_class.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.additional_class.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.additional_class.placeholder',
            ]
        ],
        'storage_method' => [
            'onChange' => 'reload',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_method.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_method.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'cookie',
                'items' => [
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_method.I.none', '', ''],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_method.I.cookie', 'cookie', ''],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_method.I.localStorage', 'localStorage', ''],
                ],
            ],
        ],
        'storage_name' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_name.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_name.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.storage_name.placeholder',
            ]
        ],
        'cookie_domain' => [
            'displayCond' => 'FIELD:storage_method:=:cookie',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_domain.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_domain.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_domain.placeholder',
            ]
        ],
        'cookie_path' => [
            'displayCond' => 'FIELD:storage_method:=:cookie',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_path.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_path.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_path.placeholder',
            ]
        ],
        'html_texts' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.html_texts.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.html_texts.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'embedded' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.embedded.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.embedded.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'group_by_purpose' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.group_by_purpose.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.group_by_purpose.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 1,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'cookie_expires_after_days' => [
            'displayCond' => 'FIELD:storage_method:=:cookie',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_expires_after_days.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_expires_after_days.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim,num',
                'default' => 60,
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.cookie_expires_after_days.placeholder',
            ]
        ],
        'default' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.default.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.default.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'must_consent' => [
            'onChange' => 'reload',
            'displayCond' => 'FIELD:notice_as_modal:=:0',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.must_consent.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.must_consent.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'accept_all' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.accept_all.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.accept_all.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 1,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'hide_decline_all' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_decline_all.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_decline_all.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'hide_learn_more' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_learn_more.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_learn_more.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ],
            ]
        ],
        'hide_toggle_all' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_toggle_all.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.hide_toggle_all.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'readOnly' => 1,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'notice_as_modal' => [
            'onChange' => 'reload',
            'displayCond' => 'FIELD:must_consent:=:0',
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.notice_as_modal.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.notice_as_modal.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'disable_powered_by' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.disable_powered_by.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.disable_powered_by.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'services' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.services.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.services.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_klaroconsentmanager_service',
                'size' => 5,
                'autoSizeMax' => 10,
                'minitems' => 0,
                'fieldControl' => [
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => false,
                    ]
                ],
            ],
        ],
        'purpose_order' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.purpose_order.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.purpose_order.description',
            'config' => []
        ],
        'callback' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.callback.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.callback.description',
            'config' => [
                'type' => 'text',
                'renderType' => 't3editor',
                'format' => 'javascript',
                'rows' => 10,
            ]
        ],
        'fluidtemplate_rootpath' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.fluidtemplate_rootpath.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.fluidtemplate_rootpath.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.fluidtemplate_rootpath.placeholder',
            ]
        ],
        'locallang_path' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.locallang_path.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.locallang_path.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'default' => '',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.locallang_path.placeholder',
            ]
        ],
        'color_scheme' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.color_scheme.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.color_scheme.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'light',
                'items' => [
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.color_scheme.I.light', 'light', 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-light.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.color_scheme.I.dark', 'dark', 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-dark.svg'],
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ]
        ],
        'alignment' => [
            'displayCond' => [
                'AND' => [
                    'FIELD:must_consent:=:0',
                    'FIELD:notice_as_modal:=:0',
                ],
            ],
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 'bottom-right',
                'items' => [
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.top-left', 'top-left', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-left.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.top-right', 'top-right', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-right.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.top-wide', 'top-wide', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-wide.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.bottom-left', 'bottom-left', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-left.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.bottom-right', 'bottom-right', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-right.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.bottom-wide', 'bottom-wide', 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-wide.svg'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.top', 'top'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.bottom', 'bottom'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.left', 'left'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.right', 'right'],
                    ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.alignment.I.wide', 'wide'],
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ]
        ],
        'append_show_button' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.append_show_button.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.append_show_button.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ],
        'append_reset_button' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.append_reset_button.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.append_reset_button.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxLabeledToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'label' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.I.disabled',
                    ]
                ]
            ]
        ]
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.general,
                    --palette--;;general_palette,
                    --palette--;;services_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.display,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.layout,
                    --palette--;;layout_palette,
                    --palette--;;consent_palette,
                    --palette--;;behavior_palette,
                    --palette--;;display_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.content,
                    --palette--;;hide_buttons_palette,
                    --palette--;;append_buttons_palette,
                    --palette--;;translations_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.cookie,
                    --palette--;;cookie_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.advanced,
                    --palette--;;setup_palette,
                    --palette--;;storage_palette,
                    --palette--;;callback_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.tabs.access,
                    --palette--;;access
            '
        ]
    ],
    'palettes' => [
        'general_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.general_palette',
            'showitem' => 'title, --linebreak--, purpose_order'
        ],
        'setup_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.setup_palette',
            'showitem' => '
                config_variable_name, --linebreak--,
                element_i_d, additional_class'
        ],
        'storage_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.storage_palette',
            'showitem' => 'storage_method, storage_name'
        ],
        'services_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.services_palette',
            'showitem' => 'services'
        ],
        'cookie_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.cookie_palette',
            'showitem' => '
                cookie_domain, --linebreak--,
                cookie_path, --linebreak--,
                cookie_expires_after_days'
        ],
        'translations_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.translations_palette',
            'showitem' => 'fluidtemplate_rootpath, locallang_path'
        ],
        'callback_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.callback_palette',
            'showitem' => 'callback'
        ],
        'consent_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.consent_palette',
            'showitem' => '
                default, accept_all
            '
        ],
        'display_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.display_palette',
            'showitem' => '
                group_by_purpose, disable_powered_by
            '
        ],
        'layout_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.layout_palette',
            'showitem' => '
                color_scheme, alignment
            '
        ],
        'behavior_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.behavior_palette',
            'showitem' => '
                must_consent, notice_as_modal, --linebreak--,
                html_texts, embedded, --linebreak--,
                testing, no_auto_load
            '
        ],
        'hide_buttons_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.hide_buttons_palette',
            'showitem' => '
                hide_decline_all, hide_learn_more, --linebreak--,
                hide_toggle_all, 
            '
        ],
        'append_buttons_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.append_buttons_palette',
            'showitem' => '
                append_show_button, append_reset_button
            '
        ],
        'access' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_configuration.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];