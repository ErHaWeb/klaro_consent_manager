<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
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

namespace ErHaWeb\KlaroConsentManager\ExpressionLanguage;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KlaroTypoScriptConditionProvider extends AbstractProvider
{
    public function __construct()
    {
        $this->expressionLanguageVariables = [
            'klaroIsActive' => $this->klaroIsActive(),
        ];
    }

    /**
     * @return bool
     */
    private function klaroIsActive(): bool
    {
        $request = $this->getRequest();
        return $request instanceof ServerRequestInterface
            && ApplicationType::fromRequest($request)->isFrontend()
            && GeneralUtility::makeInstance(KlaroService::class, $request)->getRawConfiguration();
    }

    /**
     * @return ?ServerRequestInterface
     */
    private function getRequest(): ?ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
