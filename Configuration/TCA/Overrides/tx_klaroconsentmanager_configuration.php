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

use TYPO3\CMS\Core\Utility\ArrayUtility;

defined('TYPO3') || die();

(static function () {
    ArrayUtility::mergeRecursiveWithOverrule(
        $GLOBALS['TCA']['tx_klaroconsentmanager_configuration']['columns']['purpose_order']['config'],
        $GLOBALS['TCA']['tx_klaroconsentmanager_service']['columns']['purposes']['config'],
    );
    unset($GLOBALS['TCA']['tx_klaroconsentmanager_configuration']['columns']['purpose_order']['config']['minitems']);
})();
