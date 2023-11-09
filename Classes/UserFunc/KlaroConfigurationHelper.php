<?php

namespace ErHaWeb\KlaroConsentManager\UserFunc;

use ErHaWeb\KlaroConsentManager\Service\KlaroService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;


/**
 * Example of a method in a PHP class to be called from TypoScript
 *
 */
final class KlaroConfigurationHelper
{

    /**
     * Reference to the parent (calling) cObject set from TypoScript
     *
     * @var ContentObjectRenderer
     */
    private ContentObjectRenderer $cObj;

    public function setContentObjectRenderer(ContentObjectRenderer $cObj): void
    {
        $this->cObj = $cObj;
    }

    /**
     * @return string
     */
    public function getKlaroConfiguration(): string
    {
        try {
            $klaroService = GeneralUtility::makeInstance(KlaroService::class, $this->cObj->getRequest());
            $configuration = $klaroService->getRawConfiguration();
            if ($configuration) {
                return $klaroService->getConfigurationInlineJavaScript();
            }
        } catch (ContentRenderingException $e) {
            return 'console.error("' . $e->getMessage() . '")';
        }

        return '';
    }
}