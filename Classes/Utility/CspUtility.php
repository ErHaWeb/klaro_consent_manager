<?php declare(strict_types=1);

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

namespace ErHaWeb\KlaroConsentManager\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Domain\ConsumableString;

class CspUtility
{
    public static function getNonceValue(ServerRequestInterface $request): string
    {
        $nonceAttribute = $request->getAttribute('nonce');
        if ($nonceAttribute instanceof ConsumableString) {
            return $nonceAttribute->consume();
        }
        return '';
    }
}
