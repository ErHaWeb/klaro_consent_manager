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

namespace ErHaWeb\KlaroConsentManager\Middleware;

use Doctrine\DBAL\Exception;
use ErHaWeb\KlaroConsentManager\Utility\ExtensionConfigurationUtility;
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
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $showUrl = ExtensionConfigurationUtility::getConfiguration('replaceUrl/show');
        $resetUrl = ExtensionConfigurationUtility::getConfiguration('replaceUrl/reset');

        $elementId = $this->getElementId($request);
        $searchAndReplacements = [];

        if ($showUrl) {
            $searchAndReplacements['href="' . $showUrl . '"'] = 'href="#" data-' . $elementId . '-trigger="show"';
        }
        if ($resetUrl) {
            $searchAndReplacements['href="' . $resetUrl . '"'] = 'href="#" data-' . $elementId . '-trigger="reset"';
        }

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
        $html = $response->getBody()->getContents();

        $html = $this->replaceAttrWithDataAttr($html);
        $html = str_replace(array_keys($searchAndReplacements), array_values($searchAndReplacements), $html);

        // push new content back into the response
        $body = new Stream('php://temp', 'rw');
        $body->write($html);
        return $response->withBody($body);
    }

    public function replaceAttrWithDataAttr(string $html, string $pattern = '/(<div\s+data-name="([a-zA-Z0-9\-\_]+)"\s+data-replace="([^"]+)">)((?:.|\n)*?)(<\/div>)/'): string
    {
        // Callback function to manipulate the inner HTML
        $callback = static function ($matches) {
            [$openingTag, $dataName, $dataReplace, $innerHtml, $closingTag] = $matches;

            // Remove unneeded data-replace attribute
            $openingTag = preg_replace('/ data-replace="(.*)"/i', '', $openingTag);

            // Extract attributes to replace from data-replace
            $attributesToReplace = explode(',', $dataReplace);
            if (!in_array('type', $attributesToReplace)) {
                $attributesToReplace[] = 'type';
            }

            $innerHtml = preg_replace('/<script>/', '<script type="text/javascript">', $innerHtml);

            // Loop through each attribute and replace it with "data-" prefixed version, while keeping the value
            foreach ($attributesToReplace as $attribute) {
                $replacement = sprintf('data-%s', $attribute);
                if ($attribute === 'type') {
                    $replacement = 'type="text/plain" data-type';
                }

                $innerHtml = preg_replace_callback('/<(\w+)([^>]*?)\b' . preg_quote($attribute, '/') . '="([^"]*)"\s*([^>]*?)>/', static function ($tagMatches) use ($dataName, $replacement) {
                    [$tagName, $beforeAttr, $attributeValue, $afterAttr] = $tagMatches;
                    return sprintf('<%s data-name="%s"%s%s="%s"%s>', $tagName, $dataName, $beforeAttr, $replacement, $attributeValue, $afterAttr);
                }, $innerHtml);
            }

            return $openingTag . $innerHtml . $closingTag;
        };

        // Apply the callback and get the modified HTML
        return preg_replace_callback($pattern, $callback, $html);
    }

    private function getElementId(ServerRequestInterface $request): string
    {
        $return = 'klaro';

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $configurationId = 0;

        $site = $request->getAttribute('site');
        if ($site instanceof Site) {
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
            ->executeQuery();

        try {
            $return = ($result->fetchAssociative()['element_i_d'] ?? $return) ?: $return;
        } catch (Exception) {
        }

        return $return;
    }
}
