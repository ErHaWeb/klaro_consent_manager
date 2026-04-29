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

namespace ErHaWeb\KlaroConsentManager\Utility;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KlaroUtility
{
    public static function isActive(?ServerRequestInterface $request = null): bool
    {
        if (!$request instanceof ServerRequestInterface) {
            $request = self::getRequest();
        }

        if (!$request instanceof ServerRequestInterface) {
            return false;
        }

        $cache = null;
        try {
            $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
            $cache = $cacheManager->getCache('runtime');
        } catch (NoSuchCacheException) {
            // Runtime cache is not available in some contexts (e.g., early bootstrap).
        }

        $entryIdentifier = 'klaroIsActive';

        if ($cache !== null && $cache->has($entryIdentifier)) {
            $cachedValue = $cache->get($entryIdentifier);
            if (\is_bool($cachedValue)) {
                return $cachedValue;
            }
        }

        if (!ApplicationType::fromRequest($request)->isFrontend()) {
            $cache?->set($entryIdentifier, false);
            return false;
        }

        $klaroService = GeneralUtility::makeInstance(KlaroService::class, $request);
        $isActive = !empty($klaroService->getRawConfiguration());

        $cache?->set($entryIdentifier, $isActive);

        return $isActive;
    }

    private static function getRequest(): ?ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'] ?? null;
    }
}
