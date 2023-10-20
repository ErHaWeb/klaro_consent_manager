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

namespace ErHaWeb\KlaroConsentManager\Middleware;

use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ReplaceBeforeOutput implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $elementId = $this->getElementId($request);
        $searchAndReplacements = [
            'href="https://KLARO_CONSENT.com"' => 'href="#" data-' . $elementId . '-trigger="show"',
            'href="https://KLARO_RESET.com"' => 'href="#" data-' . $elementId . '-trigger="reset"',
        ];

        if (!($GLOBALS['TSFE'] ?? [])) {
            return $handler->handle($request);
        }

        // let it generate a response
        $response = $handler->handle($request);
        if ($response instanceof NullResponse) {
            return $response;
        }

        // extract the content
        $body = $response->getBody();
        $body->rewind();
        $content = $response->getBody()->getContents();

        $content = str_replace(array_keys($searchAndReplacements), array_values($searchAndReplacements), $content);

        // push new content back into the response
        $body = new Stream('php://temp', 'rw');
        $body->write($content);
        return $response->withBody($body);
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    private function getElementId(ServerRequestInterface $request): string
    {
        $return = 'klaro';

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $configurationId = 0;

        /** @var Site $site */
        if ($site = $request->getAttribute('site')) {
            $siteConfiguration = $site->getConfiguration();
            $configurationId = (int)($siteConfiguration['klaroConfiguration'] ?? 0);
        }

        if ($configurationId === 0) {
            return $return;
        }

        if (!empty($this->configuration)) {
            return $this->configuration;
        }

        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_klaroconsentmanager_configuration');
        $result = $queryBuilder
            ->select('element_i_d')
            ->from('tx_klaroconsentmanager_configuration')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($configurationId)))
            ->execute();

        try {
            $return = ($result->fetchAssociative()['element_i_d'] ?? $return) ?: $return;
        } catch (Exception $e) {
        }

        return $return;
    }
}
