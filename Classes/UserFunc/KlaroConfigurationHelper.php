<?php

namespace ErHaWeb\KlaroConsentManager\UserFunc;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;

/**
 * Example of a method in a PHP class to be called from TypoScript
 */
final class KlaroConfigurationHelper
{
    /**
     * Reference to the parent (calling) cObject set from TypoScript
     */
    public ContentObjectRenderer $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }

    public function getKlaroConfiguration(): string
    {
        $klaroService = GeneralUtility::makeInstance(KlaroService::class, $this->getRequest());
        $configuration = $klaroService->getRawConfiguration();
        if ($configuration) {
            return $klaroService->getConfigurationInlineJavaScript();
        }

        return '';
    }

    public function getRequest(): ServerRequestInterface
    {
        if (method_exists($this->cObj, 'getRequest')) {
            try {
                return $this->cObj->getRequest();
            } catch (ContentRenderingException) {
            }
        }

        return $GLOBALS['TYPO3_REQUEST'];
    }
}
