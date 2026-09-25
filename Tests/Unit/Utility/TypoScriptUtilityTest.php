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

namespace ErHaWeb\KlaroConsentManager\Tests\Unit\Utility;

use ErHaWeb\KlaroConsentManager\Utility\TypoScriptUtility;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\TypoScript\AST\Node\RootNode;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;

final class TypoScriptUtilityTest extends TestCase
{
    #[Test]
    public function convertTypoScriptArrayToPlainArrayPreservesNodeValues(): void
    {
        $plainArray = TypoScriptUtility::convertTypoScriptArrayToPlainArray([
            'settings' => 'raw-settings-value',
            'settings.' => [
                'css.' => [
                    'klaro-default' => 'EXT:klaro_consent_manager/Resources/Public/Css/klaro.min.css',
                ],
                'configuration' => 'raw-configuration-value',
                'configuration.' => [
                    'disabled' => '0',
                ],
            ],
        ]);

        self::assertSame(
            'EXT:klaro_consent_manager/Resources/Public/Css/klaro.min.css',
            $plainArray['settings']['css']['klaro-default']
        );
        self::assertSame('0', $plainArray['settings']['configuration']['disabled']);
        self::assertSame('raw-configuration-value', $plainArray['settings']['configuration']['_typoScriptNodeValue']);
        self::assertSame('raw-settings-value', $plainArray['settings']['_typoScriptNodeValue']);
    }

    #[Test]
    public function getSettingsReturnsConfiguredPluginSettingsFromFrontendTypoScript(): void
    {
        $request = $this->createRequestWithSetup([
            'plugin.' => [
                'tx_klaroconsentmanager.' => [
                    'settings.' => [
                        'css.' => [
                            'klaro-default' => 'EXT:klaro_consent_manager/Resources/Public/Css/klaro.min.css',
                        ],
                        'configuration.' => [
                            'disabled' => '0',
                        ],
                    ],
                ],
            ],
        ]);

        self::assertSame(
            [
                'css' => [
                    'klaro-default' => 'EXT:klaro_consent_manager/Resources/Public/Css/klaro.min.css',
                ],
                'configuration' => [
                    'disabled' => '0',
                ],
            ],
            TypoScriptUtility::getSettings($request)
        );
    }

    #[Test]
    public function getSettingsReturnsEmptyArrayWhenFrontendTypoScriptIsMissing(): void
    {
        self::assertSame([], TypoScriptUtility::getSettings(new ServerRequest('GET', '/')));
    }

    #[Test]
    public function getFrameworkPreservesViewConfigurationAndCustomExtensionName(): void
    {
        $request = $this->createRequestWithSetup([
            'plugin.' => [
                'tx_customextension.' => [
                    'settings.' => ['configuration.' => ['disabled' => '0']],
                    'view.' => ['templateRootPaths.' => ['10' => 'EXT:custom/Resources/Private/Templates/']],
                ],
            ],
        ]);

        self::assertSame([
            'settings' => ['configuration' => ['disabled' => '0']],
            'view' => ['templateRootPaths' => ['10' => 'EXT:custom/Resources/Private/Templates/']],
        ], TypoScriptUtility::getFramework($request, 'CustomExtension'));
        self::assertSame(
            ['configuration' => ['disabled' => '0']],
            TypoScriptUtility::getSettings($request, 'CustomExtension')
        );
    }

    public static function unconfiguredSetupProvider(): array
    {
        return [
            'empty setup' => [[]],
            'no plugin configuration' => [['page' => 'PAGE']],
            'different plugin' => [['plugin.' => ['tx_other.' => ['settings.' => ['enabled' => '1']]]]],
        ];
    }

    #[Test]
    #[DataProvider('unconfiguredSetupProvider')]
    public function getFrameworkAndSettingsReturnEmptyArrayWithoutPluginConfiguration(array $setup): void
    {
        $request = $this->createRequestWithSetup($setup);

        self::assertSame([], TypoScriptUtility::getFramework($request));
        self::assertSame([], TypoScriptUtility::getSettings($request));
    }

    private function createRequestWithSetup(array $setup): ServerRequest
    {
        $frontendTypoScript = new FrontendTypoScript(new RootNode(), [], [], []);
        $frontendTypoScript->setSetupTree(new RootNode());
        $frontendTypoScript->setSetupArray($setup);

        return (new ServerRequest('GET', '/'))->withAttribute('frontend.typoscript', $frontendTypoScript);
    }
}
