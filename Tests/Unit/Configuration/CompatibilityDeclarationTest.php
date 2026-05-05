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

namespace ErHaWeb\KlaroConsentManager\Tests\Unit\Configuration;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CompatibilityDeclarationTest extends TestCase
{
    private const EXPECTED_TYPO3_CONSTRAINT = '^13.4 || ^14.3';
    private const EXPECTED_TYPO3_EMCONF_RANGE = '13.4.0-14.3.99';
    private const EXPECTED_PHP_CONSTRAINT = '>=8.2 <8.6';
    private const EXPECTED_PHP_EMCONF_RANGE = '8.2.0-8.5.99';

    #[Test]
    public function composerManifestPreservesTypo3AndPhpSupportMatrix(): void
    {
        $composer = $this->readComposerManifest();

        self::assertSame(self::EXPECTED_TYPO3_CONSTRAINT, $composer['require']['typo3/cms-core']);
        self::assertSame(self::EXPECTED_PHP_CONSTRAINT, $composer['require']['php']);
        self::assertSame('^9.0', $composer['require-dev']['typo3/testing-framework']);
        self::assertSame('^11.5', $composer['require-dev']['phpunit/phpunit']);

        foreach ([
            'typo3/cms-fluid-styled-content',
            'typo3/cms-install',
        ] as $packageName) {
            self::assertSame(self::EXPECTED_TYPO3_CONSTRAINT, $composer['require-dev'][$packageName]);
        }
    }

    #[Test]
    public function extEmconfStaysAlignedWithComposerCompatibilityDeclarations(): void
    {
        $emConf = $this->readExtEmconf();

        self::assertSame(self::EXPECTED_TYPO3_EMCONF_RANGE, $emConf['constraints']['depends']['typo3']);
        self::assertSame(self::EXPECTED_PHP_EMCONF_RANGE, $emConf['constraints']['depends']['php']);
    }

    /**
     * @return array{
     *     require: array<string, string>,
     *     require-dev: array<string, string>
     * }
     */
    private function readComposerManifest(): array
    {
        $composerJson = file_get_contents($this->extensionRootPath('composer.json'));
        self::assertNotFalse($composerJson);

        /** @var array{
         *     require: array<string, string>,
         *     require-dev: array<string, string>
         * } $composer
         */
        $composer = json_decode($composerJson, true, 512, JSON_THROW_ON_ERROR);

        return $composer;
    }

    /** @return array<string, mixed> */
    private function readExtEmconf(): array
    {
        $configuration = (static function (string $extEmconfPath): array {
            $_EXTKEY = 'klaro_consent_manager';
            /** @var array<string, mixed> $EM_CONF */
            $EM_CONF = [];

            require $extEmconfPath;

            $emConfValue = $EM_CONF[$_EXTKEY] ?? null;

            return is_array($emConfValue) ? $emConfValue : [];
        })($this->extensionRootPath('ext_emconf.php'));

        self::assertNotEmpty($configuration);

        return $configuration;
    }

    private function extensionRootPath(string $relativePath): string
    {
        return dirname(__DIR__, 3) . '/' . ltrim($relativePath, '/');
    }
}
