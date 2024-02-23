<?php

/**
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

$EM_CONF[$_EXTKEY] = [
    'title' => 'Klaro! Consent Manager',
    'description' => 'Functionally complete, flexible TYPO3 integration of Klaro! Consent Management by KIProtect. Klaro! is a powerful tool that protects your visitors\' privacy and data and helps you run a GDPR compliant website.',
    'category' => 'fe',
    'author' => 'Eric Harrer',
    'author_email' => 'info@eric-harrer.de',
    'author_company' => 'eric-harrer.de',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '1.7.2',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.4.99',
            'php' => '7.4.0-8.3.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'ErHaWeb\\KlaroConsentManager\\' => 'Classes',
        ],
    ],
];