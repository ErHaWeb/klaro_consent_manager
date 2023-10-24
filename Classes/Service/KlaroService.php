<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
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
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class KlaroService
{
    private const COOKIE_CONFIG = [
        'pattern' => ['type' => 'string', 'default' => ''],
        'path' => ['type' => 'string', 'default' => ''],
        'domain' => ['type' => 'string', 'default' => ''],
    ];
    private const SERVICE_CONFIG = [
        'name' => ['type' => 'string', 'default' => ''],
        'purposes' => ['type' => 'list', 'default' => ''],
        'default' => ['type' => 'boolean', 'default' => false],
        'required' => ['type' => 'boolean', 'default' => false],
        'opt_out' => ['type' => 'boolean', 'default' => false],
        'only_once' => ['type' => 'boolean', 'default' => false],
        'cookies' => ['type' => 'list', 'default' => ''],
        'callback' => ['type' => 'callback', 'default' => ''],
    ];
    private const GLOBAL_CONFIG = [
        'config_variable_name' => ['type' => 'bypass', 'default' => ''],
        'append_show_button' => ['type' => 'bypass', 'default' => false],
        'append_reset_button' => ['type' => 'bypass', 'default' => false],
        'testing' => ['type' => 'boolean', 'default' => false],
        'element_i_d' => ['type' => 'string', 'default' => ''],
        'no_auto_load' => ['type' => 'boolean', 'default' => false],
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
        'disable_powered_by' => ['type' => 'boolean', 'default' => false],
        'purpose_order' => ['type' => 'list', 'default' => ''],
        'callback' => ['type' => 'callback', 'default' => ''],

        // Special properties
        'color_scheme' => ['type' => 'bypass', 'default' => 'dark'],
        'alignment' => ['type' => 'bypass', 'default' => 'bottom-right'],
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
        'contextualConsent' => ['description', 'acceptOnce', 'acceptAlways']
    ];

    /**
     * @var int
     */
    private int $configurationId = 0;

    /**
     * @var array
     */
    private array $rawConfiguration = [];

    /**
     * @var array
     */
    private array $configuration = [];

    /**
     * @var LanguageService
     */
    private LanguageService $languageService;

    /**
     * @var ConnectionPool
     */
    private connectionPool $connectionPool;

    /**
     * @var string
     */
    private string $imprintLink = '';

    /**
     * @var string
     */
    private string $privacyPolicyLink = '';

    /**
     * @var SiteLanguage
     */
    private SiteLanguage $siteLanguage;

    /**
     * @var string
     */
    private string $locallangPath = 'EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf';

    /**
     * @var string
     */
    private string $locallangPathOverride = '';

    /**
     * @var array
     */
    private array $settings = [];

    /**
     * @var StandaloneView
     */
    private StandaloneView $view;

    /**
     * @param ServerRequestInterface $request
     * @throws InvalidConfigurationTypeException
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->initConfiguration($request);
        $this->initLanguage($request);
        $this->initConfigurationManager();
    }

    /**
     * @return array
     */
    public function getRawConfiguration(): array
    {
        return $this->rawConfiguration;
    }

    /**
     * @return string
     */
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
                    'const ' . $elementId . 'ShowElements = document.querySelectorAll("[data-' . $elementId . '-trigger=\'show\']");' .
                    'const ' . $elementId . 'ResetElements = document.querySelectorAll("[data-' . $elementId . '-trigger=\'reset\']");' .
                    $elementId . 'ShowElements.forEach(function (element) {' .
                    'element.addEventListener("click", function (e) {' .
                    'e.preventDefault();' .
                    'if (typeof ' . $configVariableName . ' !== "undefined") {' .
                    'klaro.show(' . $configVariableName . ', !0);' .
                    '}' . '});' . '});' .

                    $elementId . 'ResetElements.forEach(function (element) {' .
                    'element.addEventListener("click", function (e) {' .
                    'e.preventDefault();' .
                    'if (typeof ' . $configVariableName . ' !== "undefined") {' .
                    'klaro.show(' . $configVariableName . ', !0);' .
                    'klaro.getManager(' . $configVariableName . ').resetConsents();' .
                    '}' . '});' . '});';

                $return .= 'document.addEventListener("DOMContentLoaded",function(){"use strict";' . $appendJavaScript . '});';

                return $return;
            }

            $applicationContext = Environment::getContext();
            if ($applicationContext->isDevelopment() || $applicationContext->isTesting()) {
                if (
                    ($GLOBALS['BE_USER'] ?? []) &&
                    $beUserPreName = GeneralUtility::trimExplode(' ', $GLOBALS['BE_USER']->user['realName'])[0] ?:
                        ucfirst($GLOBALS['BE_USER']->user['username'])
                ) {
                    $label = vsprintf($this->getLabel('warning.noServicesPersonalized'), [$beUserPreName]);
                    return 'console.warn("' . $label . '")';
                }

                return 'console.warn("' . $this->getLabel('warning.noServices') . '")';
            }
        }

        return '';
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    private function initConfiguration(ServerRequestInterface $request): void
    {
        /** @var Site $site */
        if ($site = $request->getAttribute('site')) {
            $siteConfiguration = $site->getConfiguration();
            $this->configurationId = (int)($siteConfiguration['klaroConfiguration'] ?? 0);
            $this->imprintLink = $siteConfiguration['klaroImprintUrl'] ?? '';
            $this->rawConfiguration = $this->fetchConfiguration();
            $this->privacyPolicyLink = $siteConfiguration['klaroPrivacyPolicyUrl'] ?? '';
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return void
     */
    private function initLanguage(ServerRequestInterface $request): void
    {
        if ($this->siteLanguage = $request->getAttribute('language')) {
            $languageServiceFactory = GeneralUtility::makeInstance(LanguageServiceFactory::class);
            $this->languageService = $languageServiceFactory->create($this->siteLanguage->getTypo3Language());
        }
    }

    /**
     * @return void
     * @throws InvalidConfigurationTypeException
     */
    private function initConfigurationManager(): void
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        $framework = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            'KlaroConsentManager'
        );

        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'KlaroConsentManager'
        );

        $this->view = GeneralUtility::makeInstance(StandaloneView::class);
        $this->view->setLayoutRootPaths($framework['view']['layoutRootPaths'] ?? []);
        $this->view->setPartialRootPaths($framework['view']['partialRootPaths'] ?? []);
        $this->view->setTemplateRootPaths($framework['view']['templateRootPaths'] ?? []);
    }

    /**
     * @param string $elementId
     * @param bool $reset
     * @return string
     */
    private function createAppendShowButtonScript(string $elementId, bool $reset = true): string
    {
        $id = $elementId . ($reset ? 'Reset' : 'Show');
        return
            'const ' . $id . '=document.createElement("button");' .
            $id . '.setAttribute("data-klaro-trigger", "' . ($reset ? 'reset' : 'show') . '");' .
            $id . '.textContent="' . $this->getLabel('consentManager.' . ($reset ? 'reset' : 'show'), ['elementId' => $elementId, 'reset' => $reset, 'id' => $id]) . '";' .
            'document.body.appendChild(' . $id . ');';
    }

    /**
     * @return array
     */
    private function getServices(): array
    {
        $return = [];
        foreach ($this->rawConfiguration['services'] as $service) {
            $arguments = ['service' => $service];
            $serviceConfiguration = [
                'title' =>
                    $this->getFluidContent('Prepend/Service/Title', $arguments) .
                    $this->getLabel('services.' . $service['name'] . '.title', $arguments) .
                    $this->getFluidContent('Append/Service/Title', $arguments),
                'description' =>
                    $this->getFluidContent('Prepend/Service/Description', $arguments) .
                    $this->getLabel('services.' . $service['name'] . '.description', $arguments) .
                    $this->getFluidContent('Append/Service/Description', $arguments),
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
                                $pattern, $path, $domain
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

    /**
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    private function modifyValueByType($value, string $type)
    {
        switch ($type) {
            case 'callback':
                $value = 'function(consent,service){' . $value . '}';
                break;
            case 'list':
                $value = GeneralUtility::trimExplode(',', $value);
                break;
        }
        return $value;
    }

    /**
     * @param array $keys
     * @param string $prepend
     * @return array
     */
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

    /**
     * @param array $configuration
     * @param string $field
     * @param string $type
     * @return mixed
     */

    private function getConfigurationValue(array $configuration, string $field, string $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool)$configuration[$field];
            case 'integer':
                return (int)$configuration[$field];
            case 'string':
                return (string)$configuration[$field];
            default:
                return $configuration[$field];
        }
    }

    /**
     * @param array $array
     * @return string
     */
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
            } elseif ($key !== 'callback' && is_string($value)) {
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

    /**
     * @param array $array
     * @return bool
     */
    private function arrayIsAssociative(array $array): bool
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    /**
     * @return array
     */
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
                'uid' => $this->configurationId
            ]
        )) {
            $return['services'] = $this->fetchServices($return['services']);
            return $return;
        }

        return [];
    }

    /**
     * @param string $serviceUidsList
     * @return array
     */
    private function fetchServices(string $serviceUidsList): array
    {
        $serviceUids = GeneralUtility::intExplode(',', $serviceUidsList);
        $return = [];

        foreach ($serviceUids as $serviceUid) {
            if ($result = $this->fetchResults(
                'tx_klaroconsentmanager_service',
                array_keys(self::SERVICE_CONFIG),
                [
                    'uid' => $serviceUid
                ]
            )) {
                $result['cookies'] = $this->fetchCookies($serviceUid);
                $return[] = $result;
            }
        }

        return $return;
    }

    /**
     * @param int $serviceId
     * @return array
     */
    private function fetchCookies(int $serviceId): array
    {
        return $this->fetchResults(
            'tx_klaroconsentmanager_cookie',
            array_keys(self::COOKIE_CONFIG),
            [
                'parentid' => $serviceId,
                'parenttable' => 'tx_klaroconsentmanager_service'
            ],
            true
        );
    }

    /**
     * @param string $table
     * @param array $select
     * @param array $where
     * @param bool $multiple
     * @return array
     */
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
            ->execute();

        try {
            if ($return = ($multiple) ? $result->fetchAllAssociative() : $result->fetchAssociative()) {
                return $return;
            }
        } catch (Exception $e) {
        }
        return [];
    }

    private function getFluidContent(string $template = '', array $additionalArguments = []): string
    {
        $templateRootPaths = array_reverse($this->view->getTemplateRootPaths());

        //Check if a standalone template is available and extend the label accordingly
        foreach ($templateRootPaths as $templateRootPath) {
            $templateReference = $templateRootPath . $template . '.html';
            if ($templateReference !== 'php://stdin' && file_exists($templateReference)) {
                $this->view->setTemplate($template);
                $arguments = [
                    'locallang' => [
                        'defaultPath' => $this->locallangPath
                    ],
                    'configuration' => $this->configuration
                ];
                ArrayUtility::mergeRecursiveWithOverrule($arguments, $additionalArguments);
                $this->view->assignMultiple($arguments);

                if ($return = $this->view->render()) {
                    return $this->prepareStringForJavaScript($return);
                }
            }
        }

        return '';
    }

    /**
     * @param string $key
     * @param array $additionalArguments
     * @return string
     */
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
                'defaultPath' => $this->locallangPath,
                'overridePath' => $this->locallangPathOverride,
                'languageKey' => $this->siteLanguage->getTypo3Language()
            ],
            'rawConfiguration' => $this->rawConfiguration,
            'configuration' => $this->configuration,
            'links' => [
                'privacyPolicyLink' => $this->privacyPolicyLink,
                'imprintLink' => $this->imprintLink
            ]
        ];
        ArrayUtility::mergeRecursiveWithOverrule($arguments, $additionalArguments);

        if ($fluidLabel = $this->getFluidContent($template, $arguments)) {
            $label = $fluidLabel;
        }

        // If no label has been created up to this point, fall back to an error message.
        if (!$label) {
            $label = '[missing translation: ' . $key . ']';
        }

        return addslashes($this->prepareStringForJavaScript($label));
    }

    /**
     * @param string $string
     * @return string
     */
    private function prepareStringForJavaScript(string $string): string
    {
        // Trim string
        $string = trim($string);

        // Remove linebreaks
        $string = str_replace(array("\r", "\n"), '', $string);

        // Remove whitespaces and linebreaks
        $string = preg_replace('/>\\s+</', '><', $string);

        // Escape quotes
        return $string;
    }

    /**
     * @param string $typoLink
     * @return string
     */
    private function getUrlFromTypoLink(string $typoLink): string
    {
        /** @var ContentObjectRenderer $contentObject */
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);

        if ($versionInformation->getMajorVersion() < 11) {
            return $contentObject->typoLink_URL([
                'parameter' => $typoLink,
                'forceAbsoluteUrl' => true,
            ]);
        }

        return $contentObject->createUrl([
            'parameter' => $typoLink,
            'forceAbsoluteUrl' => true,
        ]);
    }
}
