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

namespace ErHaWeb\KlaroConsentManager\EventListener;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use ErHaWeb\KlaroConsentManager\Utility\CspUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\Event\BeforeStylesheetsRenderingEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class KlaroStylesheet
{
    public function __invoke(BeforeStylesheetsRenderingEvent $event): void
    {
        $request = $this->getRequest();
        if (ApplicationType::fromRequest($request)->isBackend()) {
            return;
        }

        $klaroService = GeneralUtility::makeInstance(KlaroService::class, $request);
        if (!$klaroService->getRawConfiguration()) {
            return;
        }

        if ($event->isInline()) {
            return;
        }

        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            'KlaroConsentManager'
        );
        $attributes = ['defer' => 'defer', 'nonce' => CspUtility::getNonceValue($request)];
        foreach (($settings['css'] ?? []) as $key => $css) {
            if (!($asset[$key] ?? false)) {
                $event->getAssetCollector()->addStyleSheet($key, $css, $attributes, ['priority' => true]);
            }
        }
    }

    /**
     * @return ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
