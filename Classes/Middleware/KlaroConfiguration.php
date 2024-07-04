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

namespace ErHaWeb\KlaroConsentManager\Middleware;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KlaroConfiguration implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getRequestTarget() === '/klaro-config.js') {
            $klaroService = GeneralUtility::makeInstance(KlaroService::class, $request);
            $configuration = $klaroService->getRawConfiguration();
            if ($configuration) {
                $body = new Stream('php://temp', 'wb+');
                $body->write($klaroService->getConfigurationInlineJavaScript());

                return new Response(
                    $body,
                    200,
                    ['Content-Type' => 'application/javascript; charset=utf-8']
                );
            }
        }
        return $handler->handle($request);
    }
}
