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

use ErHaWeb\KlaroConsentManager\Utility\ExtensionConfigurationUtility;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Configuration\Event\SiteConfigurationLoadedEvent;

#[AsEventListener(identifier: 'KlaroConsentManager/KlaroConfigurationRouteEnhancer')]
final readonly class KlaroConfigurationRouteEnhancer
{
    public const PAGE_TYPE = 1699541845;

    public function __invoke(SiteConfigurationLoadedEvent $event): void
    {
        $klaroConfigurationPath = ltrim((string)ExtensionConfigurationUtility::getConfiguration('klaroConfigurationPath'), '/');
        if ($klaroConfigurationPath === '') {
            return;
        }

        $configuration = $event->getConfiguration();
        $rootPageId = (int)($configuration['rootPageId'] ?? 0);

        if ($rootPageId <= 0) {
            return;
        }

        $routeEnhancer = [
            'type' => 'PageType',
            'limitToPages' => [
                $rootPageId,
            ],
            'map' => [
                $klaroConfigurationPath => self::PAGE_TYPE,
            ],
        ];

        $routeEnhancers = $configuration['routeEnhancers'] ?? [];
        if (!is_array($routeEnhancers)) {
            $routeEnhancers = [];
        }

        $routeEnhancers['KlaroConsentManagerConfiguration'] = $routeEnhancer;

        $configuration['routeEnhancers'] = $routeEnhancers;
        $event->setConfiguration($configuration);
    }
}
