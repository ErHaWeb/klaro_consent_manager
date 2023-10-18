<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Klaro! Consent Manager',
    'description' => 'Functionally complete, flexible TYPO3 integration of Klaro! Consent Management by KIProtect. Klaro! is a powerful tool that protects your visitors\' privacy and data and helps you run a GDPR compliant website.',
    'category' => 'fe',
    'author' => 'Eric Harrer',
    'author_email' => 'info@eric-harrer.de',
    'author_company' => 'eric-harrer.de',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-12.4.99',
            'php' => '7.4.0-8.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'ErHaWeb\\KlaroConsentManager\\' => 'Classes',
        ],
    ],
];
