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

namespace ErHaWeb\KlaroConsentManager\Tests\Functional\Utility;

use ErHaWeb\KlaroConsentManager\Utility\TypoScriptUtility;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScriptFactory;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class TypoScriptUtilityTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'erhaweb/klaro-consent-manager',
    ];

    public static function setupAvailabilityProvider(): array
    {
        return [
            'full setup is required' => [true],
            'fully cached page only needs config' => [false],
        ];
    }

    #[Test]
    #[DataProvider('setupAvailabilityProvider')]
    public function getFrameworkHandlesSetupAvailabilityFromCoreCache(bool $needsFullSetup): void
    {
        $connection = $this->getConnectionPool()->getConnectionForTable('sys_template');
        $connection->insert('sys_template', [
            'uid' => 1,
            'pid' => 1,
            'title' => 'Klaro TypoScript',
            'root' => 1,
            'clear' => 3,
            'config' => "page = PAGE\nconfig.debug = 0\nplugin.tx_klaroconsentmanager.settings.configuration.disabled = 0",
        ]);
        $templateRows = $connection->select(['*'], 'sys_template', ['uid' => 1])->fetchAllAssociative();
        $site = new Site('klaro-typoscript', 1, ['base' => 'https://example.com/']);
        $request = (new ServerRequest('https://example.com/'))->withAttribute('site', $site);
        $factory = $this->get(FrontendTypoScriptFactory::class);
        $cache = $this->get(CacheManager::class)->getCache('typoscript');
        $expectedFramework = ['settings' => ['configuration' => ['disabled' => '0']]];

        // First render: let the real Core factory initialize setup and populate its cache.
        $frontendTypoScript = $factory->createSettingsAndSetupConditions($site, $templateRows, [], $cache);
        $frontendTypoScript = $factory->createSetupConfigOrFullSetup(
            true, $frontendTypoScript, $site, $templateRows, [], '0', $cache, $request
        );
        self::assertSame(
            $expectedFramework,
            TypoScriptUtility::getFramework($request->withAttribute('frontend.typoscript', $frontendTypoScript))
        );

        // Subsequent request: the Core deliberately omits setup when only cached config is needed.
        $frontendTypoScript = $factory->createSettingsAndSetupConditions($site, $templateRows, [], $cache);
        $frontendTypoScript = $factory->createSetupConfigOrFullSetup(
            $needsFullSetup, $frontendTypoScript, $site, $templateRows, [], '0', $cache, $request
        );
        $request = $request->withAttribute('frontend.typoscript', $frontendTypoScript);
        self::assertInstanceOf(FrontendTypoScript::class, $request->getAttribute('frontend.typoscript'));
        self::assertSame($needsFullSetup, $frontendTypoScript->hasSetup());
        self::assertSame('0', $frontendTypoScript->getConfigArray()['debug']);

        self::assertSame($needsFullSetup ? $expectedFramework : [], TypoScriptUtility::getFramework($request));
        self::assertSame($needsFullSetup ? $expectedFramework['settings'] : [], TypoScriptUtility::getSettings($request));
    }
}
