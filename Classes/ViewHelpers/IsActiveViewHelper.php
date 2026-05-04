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

namespace ErHaWeb\KlaroConsentManager\ViewHelpers;

use ErHaWeb\KlaroConsentManager\Utility\KlaroUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class IsActiveViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    public function render(): bool
    {
        $request = $this->renderingContext->getAttribute(ServerRequestInterface::class);

        return KlaroUtility::isActive($request);
    }
}
