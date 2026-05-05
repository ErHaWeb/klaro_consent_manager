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

namespace ErHaWeb\KlaroConsentManager\Tests\Unit\Middleware;

use ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;

final class ReplaceBeforeOutputTest extends TestCase
{
    #[Test]
    public function replaceAttrWithDataAttrConvertsConfiguredAttributesInsideConsentWrapper(): void
    {
        $middleware = new ReplaceBeforeOutput($this->createMock(ConnectionPool::class));

        $html = '<div data-name="youtube" data-replace="src, href">'
            . '<iframe src="https://example.test/embed" title="Video"></iframe>'
            . '<a href="https://example.test/video">Open video</a>'
            . '</div>';

        $result = $middleware->replaceAttrWithDataAttr($html);

        self::assertStringNotContainsString('data-replace=', $result);
        self::assertStringContainsString('<div data-name="youtube">', $result);
        self::assertStringContainsString('<iframe data-src="https://example.test/embed" title="Video" data-name="youtube">', $result);
        self::assertStringContainsString('<a data-href="https://example.test/video" data-name="youtube">Open video</a>', $result);
    }

    #[Test]
    public function replaceAttrWithDataAttrKeepsHtmlWithoutConsentWrapperUnchanged(): void
    {
        $middleware = new ReplaceBeforeOutput($this->createMock(ConnectionPool::class));
        $html = '<p><a href="https://example.test">Regular link</a></p>';

        self::assertSame($html, $middleware->replaceAttrWithDataAttr($html));
    }
}
