<?php

use ErHaWeb\KlaroConsentManager\Utility\TcaUtility;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service',
        'label' => 'title',
        'label_alt' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'default_sortby' => 'ORDER BY name',
        'delete' => 'deleted',
        'iconfile' => 'EXT:klaro_consent_manager/Resources/Public/Icons/tx_klaroconsentmanager_service.svg',
        'rootLevel' => -1,
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true,
            'ignoreRootLevelRestriction' => true,
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
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'title' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.title.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.title.description',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.title.placeholder',
                'valuePicker' => [
                    'items' => [
                        ['Google AdSense', 'Google AdSense'],
                        ['Surveillance Camera', 'Surveillance Camera'],
                        ['Microsoft Clarity', 'Microsoft Clarity'],
                        ['Cloudflare', 'Cloudflare'],
                        ['External Tracker', 'External Tracker'],
                        ['Facebook Pixel', 'Facebook Pixel'],
                        ['Fast Fonts', 'Fast Fonts'],
                        ['Google Ads (formerly AdWords)', 'Google Ads (formerly AdWords)'],
                        ['Google Analytics', 'Google Analytics'],
                        ['Google Fonts', 'Google Fonts'],
                        ['Google Maps', 'Google Maps'],
                        ['Google Tag Manager', 'Google Tag Manager'],
                        ['Inline Tracker', 'Inline Tracker'],
                        ['Intercom', 'Intercom'],
                        ['Matomo/Piwik', 'Matomo/Piwik'],
                        ['Mouseflow', 'Mouseflow'],
                        ['X (formerly Twitter)', 'X (formerly Twitter)'],
                        ['Userlike', 'Userlike'],
                        ['Vimeo', 'Vimeo'],
                        ['YouTube', 'YouTube'],
                    ],
                ],
            ],
        ],
        'name' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.description',
            'config' => [
                'type' => 'input',
                'size' => 48,
                'eval' => 'nospace,alphanum_x',
                'placeholder' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.placeholder',
                'valuePicker' => [
                    'items' => [
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.adsense', 'adsense'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.camera', 'camera'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.clarity', 'clarity'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.cloudflare', 'cloudflare'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.external-tracker', 'external-tracker'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.facebook-pixel', 'facebook-pixel'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.fast-fonts', 'fast-fonts'],
                        ['LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.name.I.google-ads', 'google-ads'],
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
                'required' => true,
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
                'size' => 5,
                'autoSizeMax' => 10,
                'items' => [
                    // Klaro! Default Purposes
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.functional', 'value' => 'functional', 'icon' => '', 'group' => 'default'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.performance', 'value' => 'performance', 'icon' => '', 'group' => 'default'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.marketing', 'value' => 'marketing', 'icon' => '', 'group' => 'default'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.advertising', 'value' => 'advertising', 'icon' => '', 'group' => 'default'],

                    // Additional Purposes
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.analytics', 'value' => 'analytics', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.security', 'value' => 'security', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.livechat', 'value' => 'livechat', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.styling', 'value' => 'styling', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.videos', 'value' => 'videos', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.social', 'value' => 'social', 'icon' => '', 'group' => 'additional'],
                    ['label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.purposes.I.misc', 'value' => 'misc', 'icon' => '', 'group' => 'additional'],
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
                        'label' => '',
                        'value' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ],
                ],
            ],
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
                        'label' => '',
                        'value' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ],
                ],
            ],
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
                        'label' => '',
                        'value' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ],
                ],
            ],
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
                        'label' => '',
                        'value' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ],
                ],
            ],
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
                'renderType' => TcaUtility::getCodeEditorRenderTypeByTypo3Version(),
                'format' => 'javascript',
                'rows' => 10,
            ],
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
                        'label' => '',
                        'value' => '',
                        'labelChecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.enabled',
                        'labelUnchecked' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.I.disabled',
                    ],
                ],
            ],
        ],
        'on_accept' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_accept.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_accept.description',
            'config' => [
                'type' => 'text',
                'renderType' => TcaUtility::getCodeEditorRenderTypeByTypo3Version(),
                'format' => 'javascript',
                'rows' => 10,
            ],
        ],
        'on_init' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_init.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_init.description',
            'config' => [
                'type' => 'text',
                'renderType' => TcaUtility::getCodeEditorRenderTypeByTypo3Version(),
                'format' => 'javascript',
                'rows' => 10,
            ],
        ],
        'on_decline' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_decline.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.on_decline.description',
            'config' => [
                'type' => 'text',
                'renderType' => TcaUtility::getCodeEditorRenderTypeByTypo3Version(),
                'format' => 'javascript',
                'rows' => 10,
            ],
        ],
        'vars' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.vars.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.vars.description',
            'config' => [
                'type' => 'text',
                'renderType' => TcaUtility::getCodeEditorRenderTypeByTypo3Version(),
                'format' => 'javascript',
                'rows' => 10,
            ],
        ],
        'parentid' => [
            'label' => '',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'parenttable' => [
            'label' => '',
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
    'types' => [
        0 => [
            'showitem' => '
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.tabs.general,
                    --palette--;;general_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.tabs.cookies,
                    --palette--;;cookie_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.tabs.toggles,
                    --palette--;;toggles_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.tabs.advanced,
                    --palette--;;advanced_palette,
                --div--;LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.tabs.access,
                    --palette--;;access
            ',
        ],
    ],
    'palettes' => [
        'general_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.palettes.general_palette',
            'showitem' => '
                title, default, --linebreak--,
                name, --linebreak--, purposes
            ',
        ],
        'cookie_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.palettes.cookie_palette',
            'showitem' => '
                cookies
            ',
        ],
        'toggles_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.palettes.toggles_palette',
            'showitem' => '
                required, opt_out, --linebreak--,
                only_once, contextual_consent_only
            ',
        ],
        'advanced_palette' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.palettes.advanced_palette',
            'showitem' => '
                callback, --linebreak--,
                on_accept, --linebreak--,
                on_init, --linebreak--,
                on_decline, --linebreak--,
                vars
            ',
        ],
        'access' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tx_klaroconsentmanager_service.palettes.access_palette',
            'showitem' => 'hidden',
        ],
    ],
];
