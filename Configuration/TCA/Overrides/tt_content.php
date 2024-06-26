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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

(static function () {
    $newFields = [
        'tx_klaroconsentmanager_service' => [
            'label' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tt_content.tx_klaroconsentmanager_service.label',
            'description' => 'LLL:EXT:klaro_consent_manager/Resources/Private/Language/locallang_db.xlf:tt_content.tx_klaroconsentmanager_service.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_klaroconsentmanager_service',
                'items' => [
                    [
                        'label' => '',
                        'value' => '0',
                    ],
                ],
            ],
        ],
    ];
    ExtensionManagementUtility::addTCAcolumns(
        'tt_content',
        $newFields
    );

    ExtensionManagementUtility::addFieldsToPalette(
        'tt_content',
        'frames',
        '--linebreak--,tx_klaroconsentmanager_service',
        'after:space_after_class'
    );
})();
