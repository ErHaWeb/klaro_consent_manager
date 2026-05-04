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

use ErHaWeb\KlaroConsentManager\Service\KlaroServiceFactory;
use ErHaWeb\KlaroConsentManager\Utility\ExtensionConfigurationUtility;
use ErHaWeb\KlaroConsentManager\Utility\TypoScriptUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;
use TYPO3\CMS\Core\Site\Entity\Site;

#[AsEventListener(identifier: 'KlaroConsentManager/KlaroJavaScript')]
class KlaroJavaScript
{
    public function __construct(
        private readonly KlaroServiceFactory $klaroServiceFactory,
        private readonly Typo3Version $typo3Version,
    ) {}

    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        $request = $this->getRequest();
        if (ApplicationType::fromRequest($request)->isBackend()) {
            return;
        }

        $klaroService = $this->klaroServiceFactory->create($request);
        $configuration = $klaroService->getRawConfiguration();
        if ($configuration === []) {
            return;
        }

        $settings = TypoScriptUtility::getSettings($request);
        if ($settings === []) {
            return;
        }

        $cspOptionName = version_compare($this->typo3Version->getVersion(), '14.2.0', '>=')
            ? 'csp'
            : 'useNonce';

        $assetOptions = [
            'priority' => true,
            $cspOptionName => true,
        ];

        $asset = $event->getAssetCollector()->getJavaScripts();
        $attributes = [
            'defer' => 'defer',
        ];

        $klaroConfigurationPath = ltrim((string)ExtensionConfigurationUtility::getConfiguration('klaroConfigurationPath'), '/');
        $useInlineKlaroConfiguration = true;
        if ($klaroConfigurationPath !== '') {
            /** @var Site $site */
            $site = $request->getAttribute('site');
            $configuration = $site->getConfiguration();
            $rootPageId = (int)($configuration['rootPageId'] ?? 0);

            if ($rootPageId > 0) {
                $useInlineKlaroConfiguration = false;
                $event->getAssetCollector()->addJavaScript(
                    'klaro-config',
                    $klaroConfigurationPath,
                    $attributes,
                    $assetOptions
                );
            }
        }

        if ($useInlineKlaroConfiguration && $event->isInline()) {
            $configVariableName = $configuration['config_variable_name'] ?: 'klaroConfig';
            $asset = $event->getAssetCollector()->getInlineJavaScripts();

            if (!($asset[$configVariableName] ?? false) && $configurationInlineJavaScript = $klaroService->getConfigurationInlineJavaScript()) {
                $event->getAssetCollector()->addInlineJavaScript(
                    $configVariableName,
                    $configurationInlineJavaScript,
                    $attributes,
                    $assetOptions
                );
            }

            return;
        }

        foreach (($settings['javascript'] ?? []) as $key => $javascript) {
            if (!($asset[$key] ?? false) && $javascript) {
                if ($key === 'klaro-default' && $configuration['config_variable_name']) {
                    $attributes['data-klaro-config'] = $configuration['config_variable_name'];
                }

                $event->getAssetCollector()->addJavaScript(
                    $key,
                    $javascript,
                    $attributes,
                    $assetOptions
                );
            }
        }
    }

    private function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }
}
