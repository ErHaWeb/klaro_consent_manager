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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;

class TypoScriptUtility
{
    /**
     * @param string $extensionName
     * @return array
     */
    public static function getSettings(string $extensionName = 'KlaroConsentManager'): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            return $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
                $extensionName
            );
        } catch (InvalidConfigurationTypeException $e) {
        }

        return [];
    }

    /**
     * @param string $extensionName
     * @return array
     */
    public static function getFramework(string $extensionName = 'KlaroConsentManager'): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);

        try {
            return $configurationManager->getConfiguration(
                ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
                $extensionName
            );
        } catch (InvalidConfigurationTypeException $e) {
        }

        return [];
    }
}