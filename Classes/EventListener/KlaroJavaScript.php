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
        $configuration = $klaroService->getConfiguration();
        if (!$configuration) {
            return;
        }

        if ($event->isInline()) {
            $asset = $event->getAssetCollector()->getInlineJavaScripts();
            if (!($asset['klaroConfig'] ?? false) && $configurationInlineJavaScript = $klaroService->getConfigurationInlineJavaScript()) {
                $event->getAssetCollector()->addInlineJavaScript('klaroConfig', $configurationInlineJavaScript, ['defer' => 'defer'], ['priority' => true]);
            }
            return;
        }

        $asset = $event->getAssetCollector()->getJavaScripts();
        if (!($asset['klaro'] ?? false)) {
            $attributes = ['defer' => 'defer'];
            if ($configuration['config_variable_name']) {
                $attributes['data-klaro-config'] = $configuration['config_variable_name'];
            }
            $event->getAssetCollector()->addJavaScript(
                'klaro',
                'EXT:klaro_consent_manager/Resources/Public/JavaScript/klaro-no-translations-no-css.js',
                $attributes,
                ['priority' => true]
            );
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