<?php declare(strict_types=1);

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
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;

class TypoScriptUtility
{
    public static function getSettings(ServerRequestInterface $request, string $extensionName = 'KlaroConsentManager'): array
    {
        if ($framework = self::getFramework($request, $extensionName)) {
            return $framework['settings'] ?? [];
        }
        return [];
    }

    public static function getFramework(ServerRequestInterface $request, string $extensionName = 'KlaroConsentManager'): array
    {
        $frontendTypoScriptSetupArray = [];

        
        $frontendTypoScript = $request->getAttribute('frontend.typoscript');
        if ($frontendTypoScript) {
            $frontendTypoScriptSetupArray = $frontendTypoScript->getSetupArray();
        }

        if ($frontendTypoScriptSetupArray) {
            $pluginSignature = 'tx_' . strtolower($extensionName);
            if ($frontendTypoScriptSetupArray['plugin.'][$pluginSignature . '.'] ?? []) {
                return self::convertTypoScriptArrayToPlainArray($frontendTypoScriptSetupArray['plugin.'][$pluginSignature . '.']);
            }
        }

        return [];
    }

    public static function convertTypoScriptArrayToPlainArray(array $typoScriptArray): array
    {
        foreach ($typoScriptArray as $key => $value) {
            if (str_ends_with((string)$key, '.')) {
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
