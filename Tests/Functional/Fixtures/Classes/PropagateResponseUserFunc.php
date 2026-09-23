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

namespace ErHaWeb\KlaroConsentManager\Tests\Functional\Fixtures\Classes;

use TYPO3\CMS\Core\Attribute\AsAllowedCallable;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Http\PropagateResponseException;

/**
 * Hands a complete HTML response to TYPO3's response-propagation middleware,
 * like Extbase's ActionController::throwStatus() and page-not-found handling do.
 */
final readonly class PropagateResponseUserFunc
{
    #[AsAllowedCallable]
    public function render(string $content, array $configuration): string
    {
        throw new PropagateResponseException(
            new HtmlResponse($configuration['markup'], 404),
            1790000000
        );
    }
}
