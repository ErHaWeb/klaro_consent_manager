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

namespace ErHaWeb\KlaroConsentManager\Tests\Functional\Middleware;

use ErHaWeb\KlaroConsentManager\Tests\Functional\Fixtures\Classes\PropagateResponseUserFunc;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Configuration\SiteWriter;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

final class ReplaceBeforeOutputTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'erhaweb/klaro-consent-manager',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/KlaroFrontend.csv');
        $this->get(SiteWriter::class)->write('klaro-functional', [
            'rootPageId' => 1,
            'base' => 'https://example.com/',
        ]);
    }

    public static function contentLengthMatchesModifiedBodyDataProvider(): \Generator
    {
        yield 'body shrinks' => [
            'markup' => '<div data-name="mailchimp" data-replace="src,href"><p>Newsletter</p></div>',
            'expectedMarkup' => '<div data-name="mailchimp"><p>Newsletter</p></div>',
        ];
        yield 'body grows' => [
            'markup' => '<div data-name="youtube" data-replace="src,href">'
                . '<iframe src="https://example.com/embed"></iframe><a href="https://example.com/video">Video</a>'
                . '</div>',
            'expectedMarkup' => '<div data-name="youtube">'
                . '<iframe data-src="https://example.com/embed" data-name="youtube"></iframe>'
                . '<a data-href="https://example.com/video" data-name="youtube">Video</a>'
                . '</div>',
        ];
    }

    #[DataProvider('contentLengthMatchesModifiedBodyDataProvider')]
    #[Test]
    public function contentLengthMatchesModifiedBody(string $markup, string $expectedMarkup): void
    {
        // A stale Content-Length header is only observable if the body size changes
        self::assertNotSame(strlen($markup), strlen($expectedMarkup));

        $this->setUpFrontendRootPage(1, [], ['config' => implode("\n", [
            'page = PAGE',
            'page.10 = TEXT',
            'page.10.value = ' . $markup,
        ])]);

        $response = $this->executeFrontendSubRequest(new InternalRequest('https://example.com/'));
        $body = (string) $response->getBody();

        self::assertStringContainsString($expectedMarkup, $body);
        self::assertSame(strlen($body), (int) $response->getHeaderLine('Content-Length'));
    }

    #[Test]
    public function responsePropagatedByExceptionIsModified(): void
    {
        $this->setUpFrontendRootPage(1, [], ['config' => implode("\n", [
            'page = PAGE',
            'page.10 = USER',
            'page.10.userFunc = ' . PropagateResponseUserFunc::class . '->render',
            'page.10.markup = <div data-name="mailchimp" data-replace="src,href"><p>Newsletter</p></div>',
        ])]);

        $response = $this->executeFrontendSubRequest(new InternalRequest('https://example.com/'));

        self::assertSame(404, $response->getStatusCode());
        self::assertSame('<div data-name="mailchimp"><p>Newsletter</p></div>', (string) $response->getBody());
    }
}
