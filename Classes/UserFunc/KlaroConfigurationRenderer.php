<?php

declare(strict_types=1);

namespace ErHaWeb\KlaroConsentManager\UserFunc;

use ErHaWeb\KlaroConsentManager\Service\KlaroServiceFactory;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Attribute\AsAllowedCallable;

#[Autoconfigure(public: true)]
final class KlaroConfigurationRenderer
{
    public function __construct(
        private readonly KlaroServiceFactory $klaroServiceFactory,
    ) {}

    /**
     * @param array<string, mixed> $configuration TypoScript configuration of the USER/USER_INT object.
     */
    #[AsAllowedCallable]
    public function render(string $content, array $configuration, ServerRequestInterface $request): string
    {
        $klaroService = $this->klaroServiceFactory->create($request);

        return $klaroService->getRawConfiguration() === []
            ? ''
            : $klaroService->getConfigurationInlineJavaScript();
    }
}