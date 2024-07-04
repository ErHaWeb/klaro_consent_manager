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

    public function replaceAttrWithDataAttr($html)
    {
        // Find all elements with the 'data-replace' attribute
        $pattern = '/<([a-zA-Z0-9]+)([^>]*)data-replace="([^"]+)"([^>]*)>/';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        foreach ($matches as [$fullTag, $tagName, $beforeAttributes, $attributesToReplaceString, $afterAttributes]) {
            // Use TYPO3 GeneralUtility to trim and explode the attribute string
            $attributesToReplace = GeneralUtility::trimExplode(',', $attributesToReplaceString, true);

            // Extract the data-name attribute
            preg_match('/data-name="([^"]+)"/', $beforeAttributes, $dataNameMatches);
            $dataNameAttribute = $dataNameMatches[0] ?? '';

            // Remove data-replace attribute and extra space
            $newBeforeAttributes = preg_replace('/\s*data-replace="[^"]+"/', '', $beforeAttributes);
            $newTag = "<$tagName$newBeforeAttributes$afterAttributes>";

            // Ensure there's no trailing space before the closing '>'
            $newTag = preg_replace('/\s+>/', '>', $newTag);

            // Replace the old tag with the new tag
            $html = str_replace($fullTag, $newTag, $html);

            // Process nested elements
            $startPos = strpos($html, $newTag);
            if ($startPos !== false) {
                $endPos = strpos($html, '</' . $tagName . '>', $startPos);
                if ($endPos !== false) {
                    $endPos += strlen('</' . $tagName . '>');
                    $innerHtml = substr($html, $startPos + strlen($newTag), $endPos - $startPos - strlen($newTag));

                    // Process attributes within the inner HTML
                    $innerHtml = preg_replace_callback('/<([a-zA-Z0-9]+)([^>]*)>/', static function ($matches) use ($attributesToReplace, $dataNameAttribute) {
                        [, $innerTagName, $tagAttributes] = $matches;
                        $attributeReplaced = false;
                        foreach ($attributesToReplace as $attribute) {
                            $attributePattern = '/\s' . $attribute . '\s*=\s*"([^"]+)"/';
                            $tagAttributes = preg_replace_callback($attributePattern, static function ($attrMatches) use ($attribute) {
                                return ' data-' . $attribute . '="' . $attrMatches[1] . '"';
                            }, $tagAttributes, -1, $count);
                            if ($count > 0) {
                                $attributeReplaced = true;
                            }
                        }
                        // Add the data-name attribute to the tag only if an attribute was replaced
                        if ($attributeReplaced) {
                            $tagAttributes .= ' ' . $dataNameAttribute;
                        }
                        $newInnerTag = '<' . $innerTagName . $tagAttributes . '>';
                        return preg_replace('/\s+>/', '>', $newInnerTag);
                    }, $innerHtml);

                    $html = substr_replace($html, $innerHtml, $startPos + strlen($newTag), $endPos - $startPos - strlen($newTag));
                }
            }
        }

        return $html;
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
