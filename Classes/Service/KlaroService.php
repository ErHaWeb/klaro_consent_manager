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
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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
    private string $imprintTypoLink = '';

    /**
     * @var string
     */
    private string $privacyPolicyTypoLink = '';

    /**
     * @var string
     */
    private string $locallangPath = 'EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf';

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        /** @var Site $site */
        if ($site = $request->getAttribute('site')) {
            $siteConfiguration = $site->getConfiguration();
            $this->configurationId = (int)($siteConfiguration['klaroConfiguration'] ?? 0);
            $this->configuration = $this->fetchConfiguration();
            $this->imprintTypoLink = $siteConfiguration['klaroImprintUrl'] ?? '';
            $this->privacyPolicyTypoLink = $siteConfiguration['klaroPrivacyPolicyUrl'] ?? '';
        }

        /** @var SiteLanguage $siteLanguage */
        if ($siteLanguage = $request->getAttribute('language')) {
            $languageServiceFactory = GeneralUtility::makeInstance(LanguageServiceFactory::class);
            $this->languageService = $languageServiceFactory->create($siteLanguage->getTypo3Language());
        }
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function getConfigurationInlineJavaScript(): string
    {
        $configurationArray = [];

        if ($this->configuration) {
            $colorScheme = [];
            $alignment = [];

            foreach (self::GLOBAL_CONFIG as $key => $field) {
                $value = $this->getConfigurationValue($this->configuration, $key, $field['type']);

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
                $configurationArray[$lccKey] = $this->modifyValueByType($value, $field['type']);
            }

            if ($theme = array_merge(($colorScheme ? [$colorScheme] : []), $alignment)) {
                $configurationArray['styling']['theme'] = $theme;
            }
            if ($translations = $this->getTranslations(self::GLOBAL_LABELS)) {
                $configurationArray['translations']['zz'] = $translations;
            }
            if ($services = $this->getServices()) {
                $configurationArray['services'] = $services;

                $elementId = $this->configuration['element_i_d'] ?: 'klaro';
                $configVariableName = $this->configuration['config_variable_name'] ?: 'klaroConfig';
                $return = 'const ' . $configVariableName . '=' . $this->arrayToJavaScriptObject($configurationArray) . ';';

                $appendJavaScript = '';
                if ($this->configuration['append_show_button']) {
                    $appendJavaScript .= $this->createAppendShowButtonScript($elementId, $configVariableName, false);
                }
                if ($this->configuration['append_reset_button']) {
                    $appendJavaScript .= $this->createAppendShowButtonScript($elementId, $configVariableName);
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
        }

        return '';
    }

    /**
     * @param string $elementId
     * @param string $configVariableName
     * @param bool $reset
     * @return string
     */
    private function createAppendShowButtonScript(string $elementId, string $configVariableName, bool $reset = true): string
    {
        $id = $elementId . ($reset ? 'Reset' : 'Show');
        return
            'const ' . $id . '=document.createElement("button");' .
            $id . '.setAttribute("data-klaro-trigger", "' . ($reset ? 'reset' : 'show') . '");' .
            $id . '.textContent="' . $this->getLabel('consentManager.' . ($reset ? 'reset' : 'show')) . '";' .
            'document.body.appendChild(' . $id . ');';
    }

    /**
     * @return array
     */
    private function getServices(): array
    {
        $return = [];
        foreach ($this->configuration['services'] as $service) {
            $serviceConfiguration = [
                'title' => $this->getLabel('services.' . $service['name'] . '.title'),
                'description' => $this->getLabel('services.' . $service['name'] . '.description'),
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
            if ($privacyPolicyTypoLink = $this->getUrlFromTypoLink($this->privacyPolicyTypoLink)) {
                $return['privacyPolicyUrl'] = $privacyPolicyTypoLink;
            }

            $return['purposes'] = [];
            foreach ($this->configuration['services'] as $service) {
                $purposes = GeneralUtility::trimExplode(',', $service['purposes']);
                foreach ($purposes as $purpose) {
                    if (!($return['purposes'][$purpose] ?? false)) {
                        $return['purposes'][$purpose] = [
                            'title' => $this->getLabel('purposes.' . $purpose . '.title'),
                            'description' => $this->getLabel('purposes.' . $purpose . '.description'),
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

        if (!empty($this->configuration)) {
            return $this->configuration;
        }

        if ($return = $this->fetchResults(
            'tx_klaroconsentmanager_configuration',
            array_keys(self::GLOBAL_CONFIG),
            [
                'uid' => $this->configurationId
            ]
        )) {
            $return['services'] = $this->fetchServices($return['services']);
            $this->configuration = $return;
            return $this->configuration;
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

    /**
     * @param string $key
     * @return string
     */
    private function getLabel(string $key): string
    {
        $label = '';
        if ($this->configuration['locallang_path']) {
            $label = $this->languageService->sL('LLL:' . $this->configuration['locallang_path'] . ':' . $key);
        }
        if (!$label) {
            $label = $this->languageService->sL('LLL:' . $this->locallangPath . ':' . $key);
        }
        if (!$label) {
            $label = '[missing translation: ' . $key . ']';
        }

        return addslashes($label);
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