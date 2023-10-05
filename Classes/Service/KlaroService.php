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
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class KlaroService
{
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
    public function __construct(
        ServerRequestInterface $request
    )
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

            $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
            $languageServiceFactory = GeneralUtility::makeInstance(LanguageServiceFactory::class);
            $this->languageService = $languageServiceFactory->create($siteLanguage->getLocale());
        }
    }

    /**
     * @return array
     */
    public function getConfiguration() : array{
        return $this->configuration;
    }

    /**
     * @return string
     */
    public function getConfigurationInlineJavaScript(): string
    {
        $return = '';
        $purposesTranslations = [];

        if ($this->configuration) {
            $return .= 'testing:' . (($this->configuration['testing']) ? 'true' : 'false') . ',';
            $return .= 'elementID:\'' . (($this->configuration['element_id']) ?: 'klaro') . '\',';
            $return .= 'storageMethod:\'' . (($this->configuration['storage_method']) ?: 'cookie') . '\',';
            $return .= 'storageName:\'' . (($this->configuration['storage_name']) ?: 'klaro') . '\',';
            $return .= 'htmlTexts:' . (($this->configuration['html_texts']) ? 'true' : 'false') . ',';
            $return .= 'cookieDomain:\'' . (($this->configuration['cookie_domain']) ?: '') . '\',';
            $return .= 'cookieExpiresAfterDays:' . (($this->configuration['cookie_expires_after_days']) ?: 30) . ',';
            $return .= 'default:' . (($this->configuration['default']) ? 'true' : 'false') . ',';
            $return .= 'mustConsent:' . (($this->configuration['must_consent']) ? 'true' : 'false') . ',';
            $return .= 'acceptAll:' . (($this->configuration['accept_all']) ? 'true' : 'false') . ',';
            $return .= 'hideDeclineAll:' . (($this->configuration['hide_decline_all']) ? 'true' : 'false') . ',';
            $return .= 'hideLearnMore:' . (($this->configuration['hide_learn_more']) ? 'true' : 'false') . ',';
            if($this->configuration['callback']) {
                $return .=      'callback: function(consent, service) {';
                $return .=          $this->configuration['callback'];
                $return .=      '},';
            }
            $return .= 'services: [';

            foreach ($this->configuration['services'] as $serviceKey => $service) {

                $return .= ($serviceKey > 0 ? ',' : '') . '{';
                $return .=      'name:\'' . $service['name'] . '\',';
                $return .=      'default:' . ($service['default'] ? 'true' : 'false') . ',';
                $return .=      'required:' . (($service['required']) ? 'true' : 'false') . ',';
                $return .=      'optOut:' . (($service['opt_out']) ? 'true' : 'false') . ',';
                $return .=      'onlyOnce:' . (($service['only_once']) ? 'true' : 'false') . ',';
                $return .=      'contextualConsentOnly:' . (($service['contextual_consent_only']) ? 'true' : 'false') . ',';
                $return .=      'purposes:[';

                foreach (GeneralUtility::trimExplode(',', $service['purposes']) as $purposeKey => $purpose) {
                    $return .= ($purposeKey > 0 ? ',' : '') . '\'' . $purpose . '\'';
                    $purposesTranslations[$purpose] = $purpose . ':{';
                    $purposesTranslations[$purpose] .=      'title:\'' . $this->getLabel('purposes.' . $purpose . '.title') . '\',';
                    $purposesTranslations[$purpose] .=      'description:\'' . $this->getLabel('purposes.' . $purpose . '.description') . '\'';
                    $purposesTranslations[$purpose] .= '}';
                }

                $return .=      '],';
                $return .=      'cookies:[';

                foreach ($service['cookies'] as $cookie) {
                    $return .=      '[';
                    $return .=          '\'' . $cookie['name'] . '\'';
                    $return .=          ($cookie['path'] ? ',\'' . $cookie['path'] . '\'' : '');
                    $return .=          ($cookie['cookie_domain'] ? ',\'' . $cookie['cookie_domain'] . '\'' : '');
                    $return .=      ']';
                }
                $return .=      '],';
                if($service['callback']) {
                    $return .=      'callback: function(consent, service) {';
                    $return .=          $service['callback'];
                    $return .=      '},';
                }
                $return .=      'translations:{';
                $return .=          'zz:{';
                $return .=              'title:\'' . $this->getLabel('services.' . $service['name'] . '.title') . '\',';
                $return .=              'description:\'' . $this->getLabel('services.' . $service['name'] . '.description') . '\',';
                $return .=          '}';
                $return .=      '}';
                $return .= '}';

            }
            $return .= '],';
            $return .= 'translations:{';
            $return .=    'zz:{';
            $return .=        'privacyPolicyUrl:\'' . $this->getUrlFromTypoLink($this->privacyPolicyTypoLink) . '\',';
            $return .=        'privacyPolicy:{';
            $return .=            'name:\'' . $this->getLabel('privacyPolicy.name') . '\',';
            $return .=            'text:\'' . $this->getLabel('privacyPolicy.text') . '\'';
            $return .=        '},';
            $return .=        'consentModal:{';
            $return .=            'title:\'' . $this->getLabel('consentModal.title') . '\',';
            $return .=            'description:\'' . $this->getLabel('consentModal.description') . '\'';
            $return .=        '},';
            $return .=        'consentNotice:{';
            $return .=            'testing:\'' . $this->getLabel('consentNotice.testing') . '\',';
            $return .=            'changeDescription:\'' . $this->getLabel('consentNotice.changeDescription') . '\',';
            $return .=            'description:\'' . $this->getLabel('consentNotice.description') . '\',';
            $return .=            'learnMore:\'' . $this->getLabel('consentNotice.learnMore') . '\'';
            $return .=        '},';
            $return .=        'purposes:{' . implode(',', $purposesTranslations) . '},';
            $return .=        'purposeItem:{';
            $return .=            'service:\'' . $this->getLabel('purposeItem.service') . '\',';
            $return .=            'services:\'' . $this->getLabel('purposeItem.services') . '\'';
            $return .=        '},';
            $return .=        'ok:\'' . $this->getLabel('ok') . '\',';
            $return .=        'save:\'' . $this->getLabel('save') . '\',';
            $return .=        'decline:\'' . $this->getLabel('decline') . '\',';
            $return .=        'close:\'' . $this->getLabel('close') . '\',';
            $return .=        'acceptAll:\'' . $this->getLabel('acceptAll') . '\',';
            $return .=        'acceptSelected:\'' . $this->getLabel('acceptSelected') . '\',';
            $return .=        'service:{';
            $return .=            'disableAll:{';
            $return .=                'title:\'' . $this->getLabel('service.disableAll.title') . '\',';
            $return .=                'description:\'' . $this->getLabel('service.disableAll.description') . '\'';
            $return .=            '},';
            $return .=            'optOut:{';
            $return .=                'title:\'' . $this->getLabel('service.optOut.title') . '\',';
            $return .=                'description:\'' . $this->getLabel('service.optOut.description') . '\'';
            $return .=            '},';
            $return .=            'required:{';
            $return .=                'title:\'' . $this->getLabel('service.required.title') . '\',';
            $return .=                'description:\'' . $this->getLabel('service.required.description') . '\'';
            $return .=            '},';
            $return .=            'purposes:\'' . $this->getLabel('service.purposes') . '\',';
            $return .=            'purpose:\'' . $this->getLabel('service.purpose') . '\',';
            $return .=        '},';
            $return .=        'poweredBy:\'' . $this->getLabel('poweredBy') . '\',';
            $return .=        'contextualConsent:{';
            $return .=            'description:\'' . $this->getLabel('contextualConsent.description') . '\',';
            $return .=            'acceptOnce:\'' . $this->getLabel('contextualConsent.acceptOnce') . '\',';
            $return .=            'acceptAlways:\'' . $this->getLabel('contextualConsent.acceptAlways') . '\'';
            $return .=        '}';
            $return .=    '}';
            $return .= '}';
            $return = 'var klaroConfig={' . $return . '};';
        }

        return $return;
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

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_klaroconsentmanager_configuration');
        $result = $queryBuilder
            ->select(
                'uid',
                'title',
                'testing',
                'element_id',
                'storage_method',
                'storage_name',
                'html_texts',
                'cookie_domain',
                'cookie_expires_after_days',
                'default',
                'must_consent',
                'accept_all',
                'hide_decline_all',
                'hide_learn_more',
                'callback',
                'locallang_path'
            )
            ->from('tx_klaroconsentmanager_configuration')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($this->configurationId)))
            ->execute();

        try {
            if ($return = $result->fetchAssociative()) {
                $return['services'] = $this->fetchServices($return['uid']);
                $this->configuration = $return;
                return $this->configuration;
            }
        } catch (Exception|\Doctrine\DBAL\Driver\Exception $e) {
        }
        return [];
    }

    /**
     * @param int $configurationId
     * @return array
     */
    private function fetchServices(int $configurationId): array {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_klaroconsentmanager_service');
        $result = $queryBuilder
            ->select(
                'uid',
                'name',
                'default',
                'purposes',
                'callback',
                'required',
                'opt_out',
                'only_once',
                'contextual_consent_only'
            )
            ->from('tx_klaroconsentmanager_service')
            ->where(
                $queryBuilder->expr()->eq('parentid', $queryBuilder->createNamedParameter($configurationId)),
                $queryBuilder->expr()->eq('parenttable', $queryBuilder->createNamedParameter('tx_klaroconsentmanager_configuration'))
            )
            ->execute();
        try {
            if ($return = $result->fetchAllAssociative()) {
                foreach ($return as &$service) {
                    $service['cookies'] = $this->fetchCookies((int)$service['uid']);
                }
                return $return;
            }
        } catch (Exception|\Doctrine\DBAL\Driver\Exception $e) {
        }
        return [];
    }

    /**
     * @param int $serviceId
     * @return array
     */
    private function fetchCookies(int $serviceId): array {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_klaroconsentmanager_cookie');
        $result = $queryBuilder
            ->select(
                'name',
                'path',
                'cookie_domain'
            )
            ->from('tx_klaroconsentmanager_cookie')
            ->where(
                $queryBuilder->expr()->eq('parentid', $queryBuilder->createNamedParameter($serviceId)),
                $queryBuilder->expr()->eq('parenttable', $queryBuilder->createNamedParameter('tx_klaroconsentmanager_service'))
            )
            ->execute();
        try {
            if ($return = $result->fetchAllAssociative()) {
                return $return;
            }
        } catch (Exception|\Doctrine\DBAL\Driver\Exception $e) {
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
    private function getUrlFromTypoLink(string $typoLink): string {
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
