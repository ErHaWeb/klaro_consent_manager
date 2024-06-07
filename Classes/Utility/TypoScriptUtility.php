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

namespace ErHaWeb\KlaroConsentManager\Utility;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TypoScriptUtility
{
    /**
     * @param ServerRequestInterface $request
     * @param string $extensionName
     * @return array
     */
    public static function getSettings(ServerRequestInterface $request, string $extensionName = 'KlaroConsentManager'): array
    {
        if ($framework = self::getFramework($request, $extensionName)) {
            return $framework['settings'] ?? [];
        }
        return [];
    }

    /**
     * @param ServerRequestInterface $request
     * @param string $extensionName
     * @return array
     */
    public static function getFramework(ServerRequestInterface $request, string $extensionName = 'KlaroConsentManager'): array
    {
        $frontendTypoScriptSetupArray = [];

        $versionInformation = GeneralUtility::makeInstance(Typo3Version::class);
        if ($versionInformation->getMajorVersion() < 12) {
            $frontendTypoScriptSetupArray = $GLOBALS['TSFE']->tmpl->setup;
        } else {
            /** @var FrontendTypoScript $frontendTypoScript */
            $frontendTypoScript = $request->getAttribute('frontend.typoscript');
            if ($frontendTypoScript) {
                $frontendTypoScriptSetupArray = $frontendTypoScript->getSetupArray();
            }
        }

        if ($frontendTypoScriptSetupArray) {
            $pluginSignature = 'tx_' . strtolower($extensionName);
            if ($frontendTypoScriptSetupArray['plugin.'][$pluginSignature . '.'] ?? []) {
                return self::convertTypoScriptArrayToPlainArray($frontendTypoScriptSetupArray['plugin.'][$pluginSignature . '.']);
            }
        }

        return [];
    }

    /**
     * @param array $typoScriptArray
     * @return array
     */
    public static function convertTypoScriptArrayToPlainArray(array $typoScriptArray): array
    {
        foreach ($typoScriptArray as $key => $value) {
            if (substr((string)$key, -1) === '.') {
                $keyWithoutDot = substr((string)$key, 0, -1);
                $typoScriptNodeValue = $typoScriptArray[$keyWithoutDot] ?? null;
                if (is_array($value)) {
                    $typoScriptArray[$keyWithoutDot] = self::convertTypoScriptArrayToPlainArray($value);
                    if ($typoScriptNodeValue !== null) {
                        $typoScriptArray[$keyWithoutDot]['_typoScriptNodeValue'] = $typoScriptNodeValue;
                    }
                    unset($typoScriptArray[$key]);
                } else {
                    $typoScriptArray[$keyWithoutDot] = null;
                }
            }
        }
        return $typoScriptArray;
    }
}
