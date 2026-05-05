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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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
        $request = (new ServerRequest('GET', '/'))->withAttribute(
            'frontend.typoscript',
            new class () {
                /** @return array<string, mixed> */
                public function getSetupArray(): array
                {
                    return [
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
                    ];
                }
            }
        );

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
}
