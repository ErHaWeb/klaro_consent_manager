<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'tx-klaroconsentmanager-configuration' => [
        'provider' => BitmapIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/Extension.png',
    ],
    'tx-klaroconsentmanager-service' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/tx_klaroconsentmanager_service.svg',
    ],
    'tx-klaroconsentmanager-cookie' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/tx_klaroconsentmanager_cookie.svg',
    ],
    'tx-klaroconsentmanager-configuration-colorscheme-darkneutral' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-dark_neutral.svg',
    ],
    'tx-klaroconsentmanager-configuration-colorscheme-lightneutral' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-light_neutral.svg',
    ],
    'tx-klaroconsentmanager-configuration-colorscheme-dark' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-dark.svg',
    ],
    'tx-klaroconsentmanager-configuration-colorscheme-light' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/color_scheme-light.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-topleft' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-left.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-topright' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-right.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-topwide' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-top-wide.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-bottomleft' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-left.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-bottomright' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-right.svg',
    ],
    'tx-klaroconsentmanager-configuration-alignment-bottomwide' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:klaro_consent_manager/Resources/Public/Icons/alignment-bottom-wide.svg',
    ],
];
