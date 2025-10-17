<?php

declare(strict_types=1);

/*
 * This file is part of the "klaro_consent_manager" Extension for TYPO3 CMS.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ErHaWeb\KlaroConsentManager\Service;

use Doctrine\DBAL\Exception;
use ErHaWeb\KlaroConsentManager\Utility\TypoScriptUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class KlaroService
{
    private const COOKIE_CONFIG = [
        'identifier' => ['type' => 'bypass', 'default' => ''],
        'title' => ['type' => 'bypass', 'default' => ''],
        'pattern' => ['type' => 'string', 'default' => ''],
        'path' => ['type' => 'string', 'default' => ''],
        'domain' => ['type' => 'string', 'default' => ''],
        'expires_after' => ['type' => 'bypass', 'default' => 0],
        'expires_after_unit' => ['type' => 'bypass', 'default' => ''],
    ];
    private const SERVICE_CONFIG = [
        'name' => ['type' => 'string', 'default' => ''],
        'purposes' => ['type' => 'list', 'default' => ''],
        'default' => ['type' => 'boolean', 'default' => false],
        'required' => ['type' => 'boolean', 'default' => false],
        'opt_out' => ['type' => 'boolean', 'default' => false],
        'only_once' => ['type' => 'boolean', 'default' => false],
        'contextual_consent_only' => ['type' => 'boolean', 'default' => false],
        'cookies' => ['type' => 'list', 'default' => ''],
        'callback' => ['type' => 'callback', 'default' => ''],
        'on_accept' => ['type' => 'javascript', 'default' => ''],
        'on_init' => ['type' => 'javascript', 'default' => ''],
        'on_decline' => ['type' => 'javascript', 'default' => ''],
        'vars' => ['type' => 'object', 'default' => ''],
    ];
    private const GLOBAL_CONFIG = [
        // Setup palette (Base & Buttons)
        'config_variable_name' => ['type' => 'bypass', 'default' => ''],
        'append_show_button' => ['type' => 'bypass', 'default' => false],
        'append_reset_button' => ['type' => 'bypass', 'default' => false],
        'element_i_d' => ['type' => 'string', 'default' => ''],
        'additional_class' => ['type' => 'string', 'default' => ''],
        'style_prefix' => ['type' => 'string', 'default' => ''],

        // Storage palette (Cookie/LocalStorage)
        'storage_method' => ['type' => 'string', 'default' => 'cookie'],
        'storage_name' => ['type' => 'string', 'default' => ''],
        'cookie_domain' => ['type' => 'string', 'default' => ''],
        'cookie_path' => ['type' => 'string', 'default' => ''],
        'cookie_expires_after_days' => ['type' => 'integer', 'default' => 0],

        // Behavior palette (Notice & Runtime Behavior)
        'testing' => ['type' => 'boolean', 'default' => false],
        'no_auto_load' => ['type' => 'boolean', 'default' => false],
        'html_texts' => ['type' => 'boolean', 'default' => false],
        'embedded' => ['type' => 'boolean', 'default' => false],
        'group_by_purpose' => ['type' => 'boolean', 'default' => true],
        'default' => ['type' => 'boolean', 'default' => false],
        'must_consent' => ['type' => 'boolean', 'default' => false],
        'accept_all' => ['type' => 'boolean', 'default' => false],
        'hide_decline_all' => ['type' => 'boolean', 'default' => false],
        'hide_learn_more' => ['type' => 'boolean', 'default' => false],
        'hide_toggle_all' => ['type' => 'boolean', 'default' => false],
        'notice_as_modal' => ['type' => 'boolean', 'default' => false],
        'no_notice' => ['type' => 'boolean', 'default' => false],
        'show_notice_title' => ['type' => 'boolean', 'default' => false],
        'auto_focus' => ['type' => 'boolean', 'default' => false],
        'show_description_empty_store' => ['type' => 'boolean', 'default' => false],

        // Display palette (UI Display & Ordering)
        'disable_powered_by' => ['type' => 'boolean', 'default' => false],
        'powered_by' => ['type' => 'string', 'default' => ''],
        'purpose_order' => ['type' => 'list', 'default' => ''],

        // Styling palette (Theme & Positioning)
        'color_scheme' => ['type' => 'bypass', 'default' => 'dark_neutral'],
        'alignment' => ['type' => 'bypass', 'default' => 'bottom-right'],

        // Advanced palette (Callbacks & Hooks)
        'callback' => ['type' => 'callback', 'default' => ''],

        // Translations palette (Asset Locations)
        'fluidtemplate_rootpath' => ['type' => 'bypass', 'default' => ''],
        'locallang_path' => ['type' => 'bypass', 'default' => ''],

        // Relations palette
        'services' => ['type' => 'bypass', 'default' => ''],
    ];
    private const GLOBAL_LABELS = [
        'privacyPolicy' => ['name', 'text'],
        'consentModal' => ['title', 'description'],
        'consentNotice' => ['testing', 'title', 'changeDescription', 'description', 'learnMore'],
        'purposeItem' => ['service', 'services'],
        'ok',
        'save',
        'decline',
        'close',
        'acceptAll',
        'acceptSelected',
        'service' => ['disableAll' => ['title', 'description'], 'optOut' => ['title', 'description'], 'required' => ['title', 'description'], 'purposes', 'purpose'],
        'poweredBy',
        'contextualConsent' => ['description', 'acceptOnce', 'acceptAlways', 'descriptionEmptyStore', 'modalLinkText'],
    ];
    private const BUILTIN_STYLING_KEYS = [
        'button-text-color',
        'dark1', 'dark2', 'dark3',
        'light1', 'light2', 'light3',
        'blue1', 'blue2', 'blue3',
        'green1', 'green2', 'green3',
    ];
    private const NEUTRAL_STYLING_KEYS = [
        'dark1', 'dark3', 'light1',
    ];

    private int $configurationId = 0;
    private array $rawConfiguration = [];
    private array $configuration = [];
    private LanguageService $languageService;
    private connectionPool $connectionPool;
    private string $imprintLink = '';
    private string $privacyPolicyLink = '';
    private SiteLanguage $siteLanguage;
    private string $locallangPath = 'EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf';
    private string $locallangPathOverride = '';
    private array $framework = [];
    private array $settings = [];
    private StandaloneView $standaloneView;
    private ContentObjectRenderer $cObj;

    public function __construct(
        protected ServerRequestInterface $request
    ) {
        $this->cObj = new ContentObjectRenderer();
        $this->cObj->setRequest($request);
    }

    public function getRawConfiguration(): array
    {
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->initLanguage();

        if ($this->initConfiguration()) {
            $this->initStandaloneView();
        }

        if (($this->settings['configuration']['disabled'] ?? '') === '1') {
            return [];
        }

        return $this->rawConfiguration;
    }

    public function getConfigurationInlineJavaScript(): string
    {
        if ($this->rawConfiguration) {
            $this->configuration = $this->settings['configuration'] ?? [];
            $colorScheme = [];
            $alignment = [];

            if ($locallangPathOverride = $this->rawConfiguration['locallang_path']) {
                $this->locallangPathOverride = $locallangPathOverride;
            }

            foreach (self::GLOBAL_CONFIG as $key => $field) {
                $value = $this->getConfigurationValue($this->rawConfiguration, $key, $field['type']);

                if ($value === $field['default']) {
                    continue;
                }
                if ($key === 'color_scheme') {
                    $colorScheme = $value;
                }
                if ($key === 'alignment') {
                    $alignment = GeneralUtility::trimExplode('-', $value);
                }
                if ($field['type'] === 'bypass') {
                    continue;
                }

                $lccKey = GeneralUtility::underscoredToLowerCamelCase($key);
                $this->configuration[$lccKey] = $this->modifyValueByType($value, $field['type']);
            }

            if ($theme = array_merge(($colorScheme ? [$colorScheme] : []), $alignment)) {
                $this->configuration['styling']['theme'] = $theme;
            }
            if ($translations = $this->getTranslations(self::GLOBAL_LABELS)) {
                $this->configuration['translations']['zz'] = $translations;
            }

            if ($services = $this->getServices()) {
                $this->configuration['services'] = $services;

                // Merge final configuration array with TypoScript overrules
                ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $this->settings['configuration'] ?? []);
                $this->filterStylingForNeutralScheme();

                $elementId = $this->rawConfiguration['element_i_d'] ?: 'klaro';
                $configVariableName = $this->rawConfiguration['config_variable_name'] ?: 'klaroConfig';
                $return = 'var ' . $configVariableName . '=' . $this->arrayToJavaScriptObject($this->configuration) . ';';

                $appendJavaScript = '';
                if ($this->rawConfiguration['append_show_button']) {
                    $appendJavaScript .= $this->createAppendShowButtonScript($elementId, false);
                }
                if ($this->rawConfiguration['append_reset_button']) {
                    $appendJavaScript .= $this->createAppendShowButtonScript($elementId);
                }
                $appendJavaScript .=
                    'const ' . $elementId . 'Elements=document.querySelectorAll("[data-' . $elementId . '-trigger]");' .
                    $elementId . 'Elements.forEach(element=>{' .
                    'element.addEventListener("click",e=>{' .
                    'e.preventDefault();' .
                    'if(typeof ' . $configVariableName . '!=="undefined"){' .
                    'klaro.show(' . $configVariableName . ',true);' .
                    'if(element.dataset.' . $elementId . 'Trigger==="reset")' .
                    'klaro.getManager(' . $configVariableName . ').resetConsents();' .
                    '}});});';

                $return .= 'document.addEventListener("DOMContentLoaded",()=>{"use strict";' . $appendJavaScript . '});';

                return $return;
            }

            $applicationContext = Environment::getContext();
            if ($applicationContext->isDevelopment() || $applicationContext->isTesting()) {
                if (
                    ($GLOBALS['BE_USER'] ?? []) &&
                    $beUserPreName = GeneralUtility::trimExplode(' ', $GLOBALS['BE_USER']->user['realName'])[0] ?:
                        ucfirst((string)$GLOBALS['BE_USER']->user['username'])
                ) {
                    $beUserPreName = addslashes(strip_tags($beUserPreName));
                    $label = vsprintf($this->getLabel('warning.noServicesPersonalized'), [$beUserPreName]);
                    return 'console.warn("' . $label . '")';
                }

                return 'console.warn("' . $this->getLabel('warning.noServices') . '")';
            }
        }

        return '';
    }

    private function initConfiguration(): bool
    {
        $site = $this->request->getAttribute('site');
        if ($site instanceof Site) {
            $languageConfiguration = $this->siteLanguage->toArray();
            $configuration = [];

            if (array_key_exists('klaroConfiguration', $languageConfiguration)) {
                $configuration = $languageConfiguration;
            }

            if (!$configuration) {
                $siteConfiguration = $site->getConfiguration();
                if (array_key_exists('klaroConfiguration', $siteConfiguration)) {
                    $configuration = $siteConfiguration;
                }
            }

            if ($configuration) {
                $this->configurationId = (int)($configuration['klaroConfiguration'] ?? 0);
                if ($this->configurationId > 0) {
                    $this->imprintLink = $configuration['klaroImprintUrl'] ?? '';
                    $this->privacyPolicyLink = $configuration['klaroPrivacyPolicyUrl'] ?? '';
                    $this->rawConfiguration = $this->fetchConfiguration();
                    return true;
                }
            }
        }

        return false;
    }

    private function initLanguage(): void
    {
        $language = $this->request->getAttribute('language');
        if ($language instanceof SiteLanguage) {
            $this->siteLanguage = $language;
            $languageServiceFactory = GeneralUtility::makeInstance(LanguageServiceFactory::class);
            $this->languageService = $languageServiceFactory->create($this->siteLanguage->getTypo3Language());
        }
    }

    private function initStandaloneView(): void
    {
        $this->framework = TypoScriptUtility::getFramework($this->request);
        $this->settings = $this->framework['settings'] ?? [];

        $this->standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
        $this->standaloneView->setRequest($this->request);

        $layoutRootPaths = [];
        $partialRootPaths = [];
        $templateRootPaths = [];

        $fluidRootPath = $this->rawConfiguration['fluidtemplate_rootpath'] ?? '';

        if ($fluidRootPath) {
            $layoutRootPaths = [$fluidRootPath . 'Layouts/'];
            $partialRootPaths = [$fluidRootPath . 'Partials/'];
            $templateRootPaths = [$fluidRootPath . 'Templates/'];
        }

        $layoutRootPaths = array_merge($this->framework['view']['layoutRootPaths'] ?? [], $layoutRootPaths);
        $this->standaloneView->setLayoutRootPaths($layoutRootPaths);

        $partialRootPaths = array_merge($this->framework['view']['partialRootPaths'] ?? [], $partialRootPaths);
        $this->standaloneView->setPartialRootPaths($partialRootPaths);

        $templateRootPaths = array_merge($this->framework['view']['templateRootPaths'] ?? [], $templateRootPaths);
        $this->standaloneView->setTemplateRootPaths($templateRootPaths);
    }

    private function createAppendShowButtonScript(string $elementId, bool $reset = true): string
    {
        $id = $elementId . ($reset ? 'Reset' : 'Show');
        return 'const ' . $id . '=document.createElement("button");' .
            $id . '.setAttribute("data-' . $elementId . '-trigger", "' . ($reset ? 'reset' : 'show') . '");' .
            $id . '.textContent="' . $this->getLabel('consentManager.' . ($reset ? 'reset' : 'show'), ['elementId' => $elementId, 'reset' => $reset, 'id' => $id]) . '";' .
            'document.body.appendChild(' . $id . ');';
    }

    private function getServices(): array
    {
        $return = [];
        foreach ($this->rawConfiguration['services'] as $service) {
            if (!$this->isServiceEnabledByTypoScript($service)) {
                continue;
            }

            $arguments = ['service' => $service];
            $serviceConfiguration = [
                'translations' => [
                    'zz' => [
                        'title' =>
                            $this->getFluidContent('Prepend/Service/Title', $arguments) .
                            $this->getLabel('services.' . $service['name'] . '.title', $arguments) .
                            $this->getFluidContent('Append/Service/Title', $arguments),
                        'description' =>
                            $this->getFluidContent('Prepend/Service/Description', $arguments) .
                            $this->getLabel('services.' . $service['name'] . '.description', $arguments) .
                            $this->getFluidContent('Append/Service/Description', $arguments),
                    ],
                ],
            ];

            foreach (self::SERVICE_CONFIG as $key => $field) {
                $value = $this->getConfigurationValue($service, $key, $field['type']);
                if ($value === $field['default']) {
                    continue;
                }

                if ($key === 'cookies') {
                    $cookieConfiguration = [];
                    foreach ($service['cookies'] as $cookie) {
                        $pattern = $cookie['pattern'] ?? '';
                        $path = ($cookie['path'] !== '' && $cookie['path'] !== '/') ? $cookie['path'] : '/';
                        $domain = $cookie['domain'] ?? '';

                        if (!$pattern) {
                            continue;
                        }
                        if ($path !== '/' || $domain) {
                            $cookieConfiguration[] = [
                                $pattern, $path, $domain,
                            ];
                        } else {
                            $cookieConfiguration[] = $pattern;
                        }
                    }
                    $serviceConfiguration['cookies'] = $cookieConfiguration;
                    continue;
                }

                $lccKey = GeneralUtility::underscoredToLowerCamelCase($key);
                $serviceConfiguration[$lccKey] = $this->modifyValueByType($value, $field['type']);
            }
            $return[] = $serviceConfiguration;
        }

        return $return;
    }

    private function isServiceEnabledByTypoScript(array $service): bool
    {
        // Read TS settings for service filtering
        $settings = $this->settings['services'] ?? [];

        $name = (string)($service['name'] ?? '');
        if ($name === '') {
            return true; // no name -> do not filter
        }

        // Convert comma-separated lists to arrays
        $whitelist = [];
        if (!empty($settings['whitelist']) && is_string($settings['whitelist'])) {
            $whitelist = GeneralUtility::trimExplode(',', $settings['whitelist'], true);
        }

        $blacklist = [];
        if (!empty($settings['blacklist']) && is_string($settings['blacklist'])) {
            $blacklist = GeneralUtility::trimExplode(',', $settings['blacklist'], true);
        }

        // 1) whitelist: if defined, only listed services are allowed
        if ($whitelist !== []) {
            return in_array($name, $whitelist, true);
        }

        // 2) blacklist: deny if listed
        if ($blacklist !== [] && in_array($name, $blacklist, true)) {
            return false;
        }

        // default: enabled
        return true;
    }

    private function modifyValueByType(mixed $value, string $type): mixed
    {
        return match ($type) {
            'callback' => 'function(consent,service){' . $value . '}',
            'javascript' => 'function(handlerOpts){' . $value . '}',
            'object' => '{' . $value . '}',
            'list' => GeneralUtility::trimExplode(',', $value),
            default => $value,
        };
    }

    private function getTranslations(array $keys = [], string $prepend = ''): array
    {
        $return = [];
        foreach ($keys as $key => $label) {
            if (is_array($label)) {
                $return[$key] = $this->getTranslations($label, ($prepend !== '' ? $prepend . '.' : '') . $key);
            } else {
                $return[$label] = $this->getLabel(($prepend !== '' ? $prepend . '.' : '') . $label);
            }
        }

        if ($prepend === '') {
            if ($privacyPolicyLink = $this->getUrlFromTypoLink($this->privacyPolicyLink)) {
                $return['privacyPolicyUrl'] = $privacyPolicyLink;
            }

            $return['purposes'] = [];
            foreach ($this->rawConfiguration['services'] as $service) {
                $purposes = GeneralUtility::trimExplode(',', $service['purposes']);
                foreach ($purposes as $purpose) {
                    if (!($return['purposes'][$purpose] ?? false)) {
                        $arguments = ['service' => $service, 'purpose' => $purpose];
                        $return['purposes'][$purpose] = [
                            'title' =>
                                $this->getFluidContent('Prepend/Purpose/Title', $arguments) .
                                $this->getLabel('purposes.' . $purpose . '.title', $arguments) .
                                $this->getFluidContent('Append/Purpose/Title', $arguments),
                            'description' =>
                                $this->getFluidContent('Prepend/Purpose/Description', $arguments) .
                                $this->getLabel('purposes.' . $purpose . '.description', $arguments) .
                                $this->getFluidContent('Append/Purpose/Description', $arguments),
                        ];
                    }
                }
            }
            if (!$return['purposes']) {
                unset($return['purposes']);
            }
        }

        return $return;
    }

    private function getConfigurationValue(array $configuration, string $field, string $type): mixed
    {
        return match ($type) {
            'boolean' => (bool)$configuration[$field],
            'integer' => (int)$configuration[$field],
            'string' => (string)$configuration[$field],
            default => $configuration[$field],
        };
    }

    private function isValidJsIdentifier(string $key): bool
    {
        // Valid: foo, $foo, _foo, foo123 – but NOT: foo-bar, my key, etc.
        return (bool)preg_match('/^[A-Za-z$_][A-Za-z0-9$_]*$/', $key);
    }

    private function filterStylingForNeutralScheme(): void
    {
        $scheme = (string)($this->rawConfiguration['color_scheme'] ?? '');
        if (!in_array($scheme, ['dark_neutral', 'light_neutral'], true)) {
            return;
        }

        if (empty($this->configuration['styling']) || !is_array($this->configuration['styling'])) {
            return;
        }

        foreach (self::BUILTIN_STYLING_KEYS as $key) {
            // keep only those explicitly allowed for neutral scheme
            if (in_array($key, self::NEUTRAL_STYLING_KEYS, true)) {
                continue;
            }
            unset($this->configuration['styling'][$key]);
        }

        if ($this->configuration['styling'] === []) {
            unset($this->configuration['styling']);
        }
    }

    private function arrayToJavaScriptObject(array $array): string
    {
        $isAssociative = $this->arrayIsAssociative($array);
        $return = $isAssociative ? '{' : '[';
        foreach ($array as $key => $value) {
            if ($isAssociative) {
                if ($this->isValidJsIdentifier((string)$key)) {
                    $return .= $key . ':';
                } else {
                    $escapedKey = addslashes((string)$key);
                    $return .= '\'' . $escapedKey . '\':';
                }
            }

            if (is_array($value)) {
                $return .= $this->arrayToJavaScriptObject($value);
            } elseif (is_bool($value)) {
                $return .= $value ? 'true' : 'false';
            } elseif (
                is_string($value) &&
                !str_starts_with($value, 'function(') &&
                !str_starts_with($value, '/^') &&
                !str_ends_with($value, '$/') &&
                !(str_starts_with($value, '{') && str_ends_with($value, '}'))
            ) {
                $return .= '\'' . $value . '\'';
            } else {
                $return .= $value;
            }
            if (array_key_last($array) !== $key) {
                $return .= ',';
            }
        }
        $return .= $isAssociative ? '}' : ']';
        return $return;
    }

    private function arrayIsAssociative(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private function fetchConfiguration(): array
    {
        if ($this->configurationId === 0) {
            return [];
        }

        if (!empty($this->rawConfiguration)) {
            return $this->rawConfiguration;
        }

        if ($return = $this->fetchResults(
            'tx_klaroconsentmanager_configuration',
            array_keys(self::GLOBAL_CONFIG),
            [
                'uid' => $this->configurationId,
            ]
        )) {
            $return['services'] = $this->fetchServices($return['services']);
            return $return;
        }

        return [];
    }

    private function fetchServices(string $serviceUidsList): array
    {
        $serviceUids = GeneralUtility::intExplode(',', $serviceUidsList);
        $return = [];

        foreach ($serviceUids as $serviceUid) {
            if ($result = $this->fetchResults(
                'tx_klaroconsentmanager_service',
                array_keys(self::SERVICE_CONFIG),
                [
                    'uid' => $serviceUid,
                ]
            )) {
                $result['cookies'] = $this->fetchCookies($serviceUid);
                $return[] = $result;
            }
        }

        return $return;
    }

    private function fetchCookies(int $serviceId): array
    {
        return $this->fetchResults(
            'tx_klaroconsentmanager_cookie',
            array_keys(self::COOKIE_CONFIG),
            [
                'parentid' => $serviceId,
                'parenttable' => 'tx_klaroconsentmanager_service',
            ],
            true
        );
    }

    private function fetchResults(string $table, array $select, array $where = [], bool $multiple = false): array
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($table);

        $whereStatements = [];
        foreach ($where as $key => $value) {
            $whereStatements[] = $queryBuilder->expr()->eq($key, $queryBuilder->createNamedParameter($value));
        }

        $result = $queryBuilder
            ->select(...array_merge(['uid'], $select))
            ->from($table)
            ->where(...$whereStatements)
            ->executeQuery();

        try {
            if ($return = $multiple ? $result->fetchAllAssociative() : $result->fetchAssociative()) {
                return $return;
            }
        } catch (Exception) {
        }
        return [];
    }

    private function getFluidContent(string $template = '', array $additionalArguments = []): string
    {
        $templateRootPaths = array_reverse($this->standaloneView->getTemplateRootPaths());

        // Check if a standalone template is available and extend the label accordingly
        foreach ($templateRootPaths as $templateRootPath) {
            $templateReference = $templateRootPath . $template . '.html';
            if ($templateReference !== 'php://stdin' && file_exists($templateReference)) {
                $this->standaloneView->getRenderingContext()->setControllerAction($template);

                $arguments = [
                    'locallang' => [
                        'defaultPath' => $this->locallangPath,
                        'overridePath' => $this->locallangPathOverride,
                        'languageKey' => $this->siteLanguage->getTypo3Language(),
                    ],
                    'rawConfiguration' => $this->rawConfiguration,
                    'configuration' => $this->configuration,
                    'links' => [
                        'privacyPolicyLink' => $this->privacyPolicyLink,
                        'imprintLink' => $this->imprintLink,
                    ],
                    'framework' => $this->framework,
                ];

                ArrayUtility::mergeRecursiveWithOverrule($arguments, $additionalArguments);

                $this->standaloneView->assignMultiple($arguments);

                if ($return = $this->standaloneView->render()) {
                    return $this->prepareStringForJavaScript($return);
                }
            }
        }

        return '';
    }

    private function getLabel(string $key, array $additionalArguments = []): string
    {
        $label = '';
        $fullKey = '';
        $template = 'Labels/' . str_replace(' ', '/', ucwords(str_replace('.', ' ', $key)));

        // When in the Klaro! Configuration a Locallang file has been defined, this has priority
        if ($this->locallangPathOverride) {
            $fullKey = 'LLL:' . $this->locallangPathOverride . ':' . $key;
            $label = $this->languageService->sL($fullKey);
        }

        // If no text could be determined, fall back to the default locallang file
        if (!$label) {
            $fullKey = 'LLL:' . $this->locallangPath . ':' . $key;
            $label = $this->languageService->sL($fullKey);
        }

        $arguments = [
            'locallang' => [
                'key' => $key,
                'fullKey' => $fullKey,
                'label' => $label,
            ],
        ];

        ArrayUtility::mergeRecursiveWithOverrule($arguments, $additionalArguments);

        if ($fluidLabel = $this->getFluidContent($template, $arguments)) {
            return $fluidLabel;
        }

        // If no label has been created up to this point, fall back to an error message.
        if (!$label) {
            $label = '[missing translation: ' . $key . ']';
        }

        return $this->prepareStringForJavaScript($label);
    }

    private function prepareStringForJavaScript(string $string): string
    {
        // Remove linebreaks, remove spaces before commas and escape single quotes
        $string = str_replace(
            ["\r", "\n", ' , '],
            ['', '', ', '],
            $string
        );

        // Remove whitespaces and linebreaks
        $string = preg_replace(
            ['/\s+/', '/>\s/', '/\s</'],
            [' ', '>', '<'],
            $string
        );

        // Add slashes
        $string = addslashes((string)$string);

        // Trim string
        return trim($string);
    }

    private function getUrlFromTypoLink(string $typoLink): string
    {
        return $this->cObj->createUrl([
            'parameter' => $typoLink,
            'forceAbsoluteUrl' => true,
        ]);
    }
}
