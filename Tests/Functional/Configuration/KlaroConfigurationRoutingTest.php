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

namespace ErHaWeb\KlaroConsentManager\Tests\Functional\Configuration;

use ErHaWeb\KlaroConsentManager\EventListener\KlaroConfigurationRouteEnhancer;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Routing\SiteRouteResult;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

final class KlaroConfigurationRoutingTest extends FunctionalTestCase
{
    private const ROOT_PAGE_ID = 1;
    private const KLARO_CONFIGURATION_ID = 1;

    protected array $testExtensionsToLoad = [
        'erhaweb/klaro-consent-manager',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/KlaroFrontend.csv');
        $this->setUpFrontendRootPage(
            self::ROOT_PAGE_ID,
            [
                'constants' => [
                    'EXT:klaro_consent_manager/Configuration/TypoScript/constants.typoscript',
                ],
                'setup' => [
                    'EXT:klaro_consent_manager/Configuration/TypoScript/setup.typoscript',
                    'EXT:klaro_consent_manager/Tests/Functional/Fixtures/TypoScript/KlaroFrontend.typoscript',
                ],
            ],
            [
                'title' => 'Klaro functional TypoScript',
            ]
        );
    }

    #[Test]
    public function routeEnhancerMapsConfiguredPathAsRelativePageTypeRoute(): void
    {
        foreach ([
            '/klaro-config.js' => 'klaro-config.js',
            'custom-klaro.js' => 'custom-klaro.js',
            '/consent/klaro-config.custom.js' => 'consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => 'assets/klaro/config.js',
            '/consent/klaro-config.js/' => 'consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedRoutePath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/path/',
                $configuredKlaroPath
            );

            $routeEnhancers = $site->getConfiguration()['routeEnhancers'] ?? [];
            self::assertIsArray($routeEnhancers, $configuredKlaroPath);
            self::assertArrayHasKey('KlaroConsentManagerConfiguration', $routeEnhancers, $configuredKlaroPath);

            $routeEnhancer = $routeEnhancers['KlaroConsentManagerConfiguration'];
            self::assertIsArray($routeEnhancer, $configuredKlaroPath);

            self::assertSame('PageType', $routeEnhancer['type'] ?? null, $configuredKlaroPath);
            self::assertSame([self::ROOT_PAGE_ID], $routeEnhancer['limitToPages'] ?? null, $configuredKlaroPath);
            self::assertSame(
                [$expectedRoutePath => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                $routeEnhancer['map'] ?? null,
                $configuredKlaroPath
            );
        }
    }

    #[Test]
    public function generatedScriptUrlUsesConfiguredPathOnRootSite(): void
    {
        foreach ([
            '/klaro-config.js' => '/klaro-config.js',
            'custom-klaro.js' => '/custom-klaro.js',
            '/consent/klaro-config.custom.js' => '/consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => '/assets/klaro/config.js',
            '/consent/klaro-config.js/' => '/consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedScriptPath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/',
                $configuredKlaroPath
            );

            self::assertSame(
                $expectedScriptPath,
                (string) $site->getRouter()->generateUri(
                    self::ROOT_PAGE_ID,
                    ['type' => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                    '',
                    RouterInterface::ABSOLUTE_PATH
                ),
                $configuredKlaroPath
            );
        }
    }

    #[Test]
    public function generatedScriptUrlPrefixesConfiguredPathWithSiteBasePath(): void
    {
        foreach ([
            '/klaro-config.js' => '/path/klaro-config.js',
            'custom-klaro.js' => '/path/custom-klaro.js',
            '/consent/klaro-config.custom.js' => '/path/consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => '/path/assets/klaro/config.js',
            '/consent/klaro-config.js/' => '/path/consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedScriptPath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/path/',
                $configuredKlaroPath
            );

            self::assertSame(
                $expectedScriptPath,
                (string) $site->getRouter()->generateUri(
                    self::ROOT_PAGE_ID,
                    ['type' => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                    '',
                    RouterInterface::ABSOLUTE_PATH
                ),
                $configuredKlaroPath
            );
        }
    }

    #[Test]
    public function frontendPageReferencesGeneratedKlaroConfigurationPathBelowSiteBasePath(): void
    {
        foreach ([
            '/klaro-config.js' => '/path/klaro-config.js',
            'custom-klaro.js' => '/path/custom-klaro.js',
            '/consent/klaro-config.custom.js' => '/path/consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => '/path/assets/klaro/config.js',
            '/consent/klaro-config.js/' => '/path/consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedScriptPath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/path/',
                $configuredKlaroPath
            );
            $generatedScriptPath = (string) $site->getRouter()->generateUri(
                self::ROOT_PAGE_ID,
                ['type' => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                '',
                RouterInterface::ABSOLUTE_PATH
            );

            $pageResponse = $this->executeFrontendSubRequest(
                new InternalRequest('https://example.com/path/')
            );

            self::assertSame($expectedScriptPath, $generatedScriptPath, $configuredKlaroPath);
            self::assertSame(200, $pageResponse->getStatusCode(), $configuredKlaroPath);
            self::assertMatchesRegularExpression(
                '#<script\b[^>]*\bsrc="' . preg_quote($expectedScriptPath, '#') . '(?:\?[^"]*)?"#',
                (string) $pageResponse->getBody(),
                $configuredKlaroPath
            );
        }
    }

    #[Test]
    public function generatedKlaroConfigurationPathBelowSiteBasePathMapsToConfiguredPageType(): void
    {
        foreach ([
            '/klaro-config.js' => '/path/klaro-config.js',
            'custom-klaro.js' => '/path/custom-klaro.js',
            '/consent/klaro-config.custom.js' => '/path/consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => '/path/assets/klaro/config.js',
            '/consent/klaro-config.js/' => '/path/consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedScriptPath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/path/',
                $configuredKlaroPath
            );
            $generatedConfigurationPath = (string) $site->getRouter()->generateUri(
                self::ROOT_PAGE_ID,
                ['type' => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                '',
                RouterInterface::ABSOLUTE_PATH
            );
            $configurationRequest = new InternalRequest(
                'https://example.com' . $generatedConfigurationPath
            );
            $siteRouteResult = $this->get(SiteMatcher::class)->matchRequest($configurationRequest);
            self::assertInstanceOf(SiteRouteResult::class, $siteRouteResult, $configuredKlaroPath);

            $pageArguments = $site->getRouter()->matchRequest($configurationRequest, $siteRouteResult);
            self::assertInstanceOf(PageArguments::class, $pageArguments, $configuredKlaroPath);

            self::assertSame($expectedScriptPath, $generatedConfigurationPath, $configuredKlaroPath);
            self::assertSame(self::ROOT_PAGE_ID, $pageArguments->getPageId(), $configuredKlaroPath);
            self::assertSame(
                (string) KlaroConfigurationRouteEnhancer::PAGE_TYPE,
                $pageArguments->getPageType(),
                $configuredKlaroPath
            );
        }
    }

    #[Test]
    public function generatedKlaroConfigurationPathBelowSiteBasePathReturnsJavaScriptAndNotHtml(): void
    {
        foreach ([
            '/klaro-config.js' => '/path/klaro-config.js',
            'custom-klaro.js' => '/path/custom-klaro.js',
            '/consent/klaro-config.custom.js' => '/path/consent/klaro-config.custom.js',
            'URI:/assets/klaro/config.js' => '/path/assets/klaro/config.js',
            '/consent/klaro-config.js/' => '/path/consent/klaro-config.js',
        ] as $configuredKlaroPath => $expectedScriptPath) {
            $site = $this->configureSiteBaseAndKlaroConfigurationPath(
                'https://example.com/path/',
                $configuredKlaroPath
            );
            $generatedConfigurationPath = (string) $site->getRouter()->generateUri(
                self::ROOT_PAGE_ID,
                ['type' => KlaroConfigurationRouteEnhancer::PAGE_TYPE],
                '',
                RouterInterface::ABSOLUTE_PATH
            );
            $configurationRequest = new InternalRequest(
                'https://example.com' . $generatedConfigurationPath
            );

            $configurationResponse = $this->executeFrontendSubRequest($configurationRequest);
            $contentType = $configurationResponse->getHeaderLine('Content-Type');
            $body = (string) $configurationResponse->getBody();

            self::assertSame($expectedScriptPath, $generatedConfigurationPath, $configuredKlaroPath);
            self::assertSame(200, $configurationResponse->getStatusCode(), $configuredKlaroPath);
            self::assertStringStartsWith('application/javascript', $contentType, $configuredKlaroPath);
            self::assertFalse(str_starts_with($contentType, 'text/html'), $configuredKlaroPath);
            self::assertStringContainsString('var klaroConfig=', $body, $configuredKlaroPath);
            self::assertStringNotContainsString('<!doctype html', strtolower($body), $configuredKlaroPath);
            self::assertStringNotContainsString('<html', strtolower($body), $configuredKlaroPath);
            self::assertFalse(
                str_starts_with($contentType, 'text/html')
                && strtolower($configurationResponse->getHeaderLine('X-Content-Type-Options')) === 'nosniff',
                $configuredKlaroPath
            );
        }
    }

    private function configureSiteBaseAndKlaroConfigurationPath(string $siteBase, string $klaroConfigurationPath): Site
    {
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['klaro_consent_manager']['klaroConfigurationPath'] = $klaroConfigurationPath;

        $siteConfigurationPath = $this->instancePath . '/typo3conf/sites/klaro-functional';
        GeneralUtility::mkdir_deep($siteConfigurationPath);
        file_put_contents(
            $siteConfigurationPath . '/config.yaml',
            Yaml::dump([
                'rootPageId' => self::ROOT_PAGE_ID,
                'base' => $siteBase,
                'klaroConfiguration' => self::KLARO_CONFIGURATION_ID,
                'klaroImprintUrl' => '',
                'klaroPrivacyPolicyUrl' => '',
            ], 99, 2)
        );

        $cacheManager = $this->get(CacheManager::class);
        $cacheManager->getCache('core')->flush();
        $cacheManager->getCache('runtime')->flush();

        return $this->get(SiteFinder::class)->getSiteByRootPageId(self::ROOT_PAGE_ID);
    }
}
