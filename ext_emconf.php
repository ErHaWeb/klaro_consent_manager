<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Klaro! Consent Manager',
    'description' => 'Functionally complete, flexible TYPO3 integration of Klaro! Consent Management by KIProtect GmbH, a powerful tool that protects your visitors\' privacy and data.',
    'category' => 'fe',
    'author' => 'Eric Harrer',
    'author_email' => 'info@eric-harrer.de',
    'author_company' => 'eric-harrer.de',
    'state' => 'stable',
    'version' => '3.0.4',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.3.99',
            'php' => '8.2.0-8.5.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'ErHaWeb\\KlaroConsentManager\\' => 'Classes',
        ],
    ],
];
