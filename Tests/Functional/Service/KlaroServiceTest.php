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

namespace ErHaWeb\KlaroConsentManager\Tests\Functional\Service;

use ErHaWeb\KlaroConsentManager\Service\KlaroServiceFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class KlaroServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'erhaweb/klaro-consent-manager',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/KlaroFrontend.csv');
        $this->getConnectionPool()->getConnectionForTable('tx_klaroconsentmanager_configuration')->insert(
            'tx_klaroconsentmanager_configuration',
            ['uid' => 2, 'title' => 'Language configuration', 'config_variable_name' => 'languageConfig', 'services' => '1']
        );
    }

    public static function configurationSelectionProvider(): array
    {
        return [
            'site configuration fallback' => [[], 1, 'klaroConfig'],
            'language configuration overrides site configuration' => [['klaroConfiguration' => 2], 2, 'languageConfig'],
        ];
    }

    #[Test]
    #[DataProvider('configurationSelectionProvider')]
    public function getRawConfigurationLoadsConfigurationAndServicesWithSiteLanguage(
        array $languageConfiguration,
        int $expectedConfigurationId,
        string $expectedVariableName
    ): void {
        $request = $this->createSiteRequest('/en/', $languageConfiguration);
        self::assertInstanceOf(SiteLanguage::class, $request->getAttribute('language'));

        $configuration = $this->get(KlaroServiceFactory::class)->create($request)->getRawConfiguration();

        self::assertSame($expectedConfigurationId, $configuration['uid']);
        self::assertSame($expectedVariableName, $configuration['config_variable_name']);
        self::assertCount(1, $configuration['services']);
        self::assertSame('functional', $configuration['services'][0]['name']);
    }

    #[Test]
    public function getRawConfigurationRespectsDisabledLanguageConfiguration(): void
    {
        $request = $this->createSiteRequest('/en/', ['klaroConfiguration' => 0]);
        self::assertInstanceOf(SiteLanguage::class, $request->getAttribute('language'));

        $service = $this->get(KlaroServiceFactory::class)->create($request);

        self::assertSame([], $service->getRawConfiguration());
        self::assertSame('', $service->getConfigurationInlineJavaScript());
    }

    #[Test]
    public function getRawConfigurationReturnsEmptyArrayWithoutSite(): void
    {
        $service = $this->get(KlaroServiceFactory::class)->create(new ServerRequest('https://example.com/'));

        self::assertSame([], $service->getRawConfiguration());
        self::assertSame('', $service->getConfigurationInlineJavaScript());
    }

    #[Test]
    public function getRawConfigurationReturnsEmptyArrayWhenSiteHasNoResolvedLanguage(): void
    {
        // The site base matches, but the only language has an /en/ prefix.
        $request = $this->createSiteRequest('/');
        self::assertInstanceOf(Site::class, $request->getAttribute('site'));
        self::assertArrayHasKey('language', $request->getAttributes());
        self::assertNull($request->getAttribute('language'));

        $service = $this->get(KlaroServiceFactory::class)->create($request);

        self::assertSame([], $service->getRawConfiguration());
        self::assertSame('', $service->getConfigurationInlineJavaScript());
    }

    private function createSiteRequest(string $path, array $languageConfiguration = []): ServerRequestInterface
    {
        $siteConfigurationPath = $this->instancePath . '/typo3conf/sites/klaro-service';
        GeneralUtility::mkdir_deep($siteConfigurationPath);
        file_put_contents(
            $siteConfigurationPath . '/config.yaml',
            Yaml::dump([
                'rootPageId' => 1,
                'base' => 'https://example.com/',
                'klaroConfiguration' => 1,
                'languages' => [array_replace([
                    'languageId' => 0,
                    'title' => 'English',
                    'locale' => 'en_US.UTF-8',
                    'base' => '/en/',
                ], $languageConfiguration)],
            ], 99, 2)
        );
        $cacheManager = $this->get(CacheManager::class);
        $cacheManager->getCache('core')->flush();
        $cacheManager->getCache('runtime')->flush();

        $request = new ServerRequest('https://example.com' . $path);
        $routeResult = $this->get(SiteMatcher::class)->matchRequest($request);

        // Forward the real routing result in the same way as SiteResolver.
        return $request
            ->withAttribute('site', $routeResult->getSite())
            ->withAttribute('language', $routeResult->getLanguage())
            ->withAttribute('routing', $routeResult);
    }
}
