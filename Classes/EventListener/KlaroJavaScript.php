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

namespace ErHaWeb\KlaroConsentManager\EventListener;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use ErHaWeb\KlaroConsentManager\Utility\CspUtility;
use ErHaWeb\KlaroConsentManager\Utility\TypoScriptUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KlaroJavaScript
{
    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        $request = $this->getRequest();
        if (ApplicationType::fromRequest($request)->isBackend()) {
            return;
        }

        $klaroService = GeneralUtility::makeInstance(KlaroService::class, $request);
        $configuration = $klaroService->getRawConfiguration();
        if (!$configuration) {
            return;
        }

        $settings = TypoScriptUtility::getSettings($request);
        if (!$settings) {
            return;
        }

        if ($event->isInline()) {
            $configVariableName = $configuration['config_variable_name'] ?: 'klaroConfig';
            $asset = $event->getAssetCollector()->getInlineJavaScripts();
            if (!($asset[$configVariableName] ?? false) && $configurationInlineJavaScript = $klaroService->getConfigurationInlineJavaScript()) {
                $attributes = [
                    'defer' => 'defer',
                    'nonce' => CspUtility::getNonceValue($request),
                ];
                $event->getAssetCollector()->addInlineJavaScript($configVariableName, $configurationInlineJavaScript, $attributes, ['priority' => true]);
            }
            return;
        }

        $asset = $event->getAssetCollector()->getJavaScripts();
        $attributes = ['defer' => 'defer', 'nonce' => CspUtility::getNonceValue($request)];

        foreach (($settings['javascript'] ?? []) as $key => $javascript) {
            if (!($asset[$key] ?? false) && $javascript) {
                if ($key === 'klaro-default' && $configuration['config_variable_name']) {
                    $attributes['data-klaro-config'] = $configuration['config_variable_name'];
                }
                $event->getAssetCollector()->addJavaScript($key, $javascript, $attributes, ['priority' => true]);
            }
        }
    }

    private function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
