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

use ErHaWeb\KlaroConsentManager\ExpressionLanguage\KlaroTypoScriptConditionProvider;
use ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput;
use ErHaWeb\KlaroConsentManager\Service\KlaroServiceFactory;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class ExtensionBootstrapTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'erhaweb/klaro-consent-manager',
    ];

    #[Test]
    public function dependencyInjectionContainerProvidesKlaroServiceFactory(): void
    {
        self::assertInstanceOf(KlaroServiceFactory::class, $this->get(KlaroServiceFactory::class));
    }

    #[Test]
    public function extensionDatabaseTablesAndTcaColumnsAreAvailable(): void
    {
        $connection = $this->get(ConnectionPool::class)->getConnectionForTable('tx_klaroconsentmanager_configuration');
        $connection->insert('tx_klaroconsentmanager_configuration', [
            'title' => 'Functional configuration',
        ]);

        self::assertSame(
            1,
            $connection->count(
                '*',
                'tx_klaroconsentmanager_configuration',
                ['title' => 'Functional configuration']
            )
        );
        self::assertArrayHasKey(
            'tx_klaroconsentmanager_service',
            $GLOBALS['TCA']['tt_content']['columns'] ?? []
        );
    }

    #[Test]
    public function extensionConfigurationFilesExposeMiddlewareAndExpressionLanguageContracts(): void
    {
        $extensionRoot = dirname(__DIR__, 3);
        $middlewareConfiguration = require $extensionRoot . '/Configuration/RequestMiddlewares.php';
        $expressionLanguageConfiguration = require $extensionRoot . '/Configuration/ExpressionLanguage.php';

        self::assertSame(
            ReplaceBeforeOutput::class,
            $middlewareConfiguration['frontend']['erhaweb/klaro-consent-manager/replace-content']['target']
        );
        self::assertSame(
            [
                KlaroTypoScriptConditionProvider::class,
            ],
            $expressionLanguageConfiguration['typoscript']
        );
    }
}
