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
        'config_variable_name' => ['type' => 'bypass', 'default' => ''],
        'append_show_button' => ['type' => 'bypass', 'default' => false],
        'append_reset_button' => ['type' => 'bypass', 'default' => false],
        'testing' => ['type' => 'boolean', 'default' => false],
        'element_i_d' => ['type' => 'string', 'default' => ''],
        'no_auto_load' => ['type' => 'boolean', 'default' => false],
        'show_description_empty_store' => ['type' => 'boolean', 'default' => false],
        'additional_class' => ['type' => 'string', 'default' => ''],
        'storage_method' => ['type' => 'string', 'default' => 'cookie'],
        'storage_name' => ['type' => 'string', 'default' => ''],
        'cookie_domain' => ['type' => 'string', 'default' => ''],
        'cookie_path' => ['type' => 'string', 'default' => ''],
        'html_texts' => ['type' => 'boolean', 'default' => false],
        'embedded' => ['type' => 'boolean', 'default' => false],
        'group_by_purpose' => ['type' => 'boolean', 'default' => true],
        'cookie_expires_after_days' => ['type' => 'integer', 'default' => 0],
        'default' => ['type' => 'boolean', 'default' => false],
        'must_consent' => ['type' => 'boolean', 'default' => false],
        'accept_all' => ['type' => 'boolean', 'default' => false],
        'hide_decline_all' => ['type' => 'boolean', 'default' => false],
        'hide_learn_more' => ['type' => 'boolean', 'default' => false],
        'hide_toggle_all' => ['type' => 'boolean', 'default' => false],
        'notice_as_modal' => ['type' => 'boolean', 'default' => false],
        'show_notice_title' => ['type' => 'boolean', 'default' => false],
        'auto_focus' => ['type' => 'boolean', 'default' => false],
        'disable_powered_by' => ['type' => 'boolean', 'default' => false],
        'purpose_order' => ['type' => 'list', 'default' => ''],
        'callback' => ['type' => 'callback', 'default' => ''],

        // Special properties
        'color_scheme' => ['type' => 'bypass', 'default' => 'dark_neutral'],
        'alignment' => ['type' => 'bypass', 'default' => 'bottom-right'],
        'fluidtemplate_rootpath' => ['type' => 'bypass', 'default' => ''],
        'locallang_path' => ['type' => 'bypass', 'default' => ''],

        // Relations
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

    private function arrayToJavaScriptObject(array $array): string
    {
        $isAssociative = $this->arrayIsAssociative($array);
        $return = $isAssociative ? '{' : '[';
        foreach ($array as $key => $value) {
            $return .= $isAssociative ? $key . ':' : '';
            if (is_array($value)) {
                $return .= $this->arrayToJavaScriptObject($value);
            } elseif (is_bool($value)) {
                $return .= $value ? 'true' : 'false';
            } elseif (
                is_string($value) &&
                !str_starts_with($value, '{') &&
                !str_starts_with($value, 'function(') &&
                !str_starts_with($value, '/^') &&
                !str_ends_with($value, '$/')
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
