<?php

declare(strict_types=1);

namespace ErHaWeb\KlaroConsentManager\Command;

use DOMDocument;
use DOMElement;
use DOMException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'klaro:yaml-to-xliff',
    description: 'Converts YAML translations (e.g. from src/translations) to XLIFF 1.2 (TYPO3-compatible file names).'
)]
final class YamlToXliffCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::REQUIRED, 'Input directory containing *.yml/*.yaml files (e.g. src/translations)')
            ->addArgument('output', InputArgument::REQUIRED, 'Target directory for generated XLIFF files (will be created if it does not exist)')
            ->addOption('base', null, InputOption::VALUE_REQUIRED, 'Base language (e.g. "en"). Base → locallang.xlf, others → <iso>.locallang.xlf')
            ->addOption('product-name', null, InputOption::VALUE_REQUIRED, 'Optional product-name attribute in the <file> tag', 'klaro_consent_manager')
            ->addOption('original', null, InputOption::VALUE_REQUIRED, 'Optional original attribute in the <file> tag', 'EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf');
    }

    /**
     * @throws DOMException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inDir       = rtrim((string)$input->getArgument('input'), '/');
        $outDir      = rtrim((string)$input->getArgument('output'), '/');
        $baseLocale  = (string)($input->getOption('base') ?? '');
        $productName = (string)$input->getOption('product-name');
        $original    = (string)$input->getOption('original');

        if (!is_dir($inDir)) {
            $output->writeln("<error>Input directory not found: $inDir</error>");
            return Command::FAILURE;
        }
        if (!is_dir($outDir) && !mkdir($outDir, 0777, true) && !is_dir($outDir)) {
            $output->writeln("<error>Could not create output directory: $outDir</error>");
            return Command::FAILURE;
        }

        // Collect YAML files (top-level is sufficient for Klaro structure)
        $files = array_values(array_filter(
            scandir($inDir) ?: [],
            static fn (string $f): bool => (bool)preg_match('/\.(ya?ml)$/i', $f)
        ));
        if ($files === []) {
            $output->writeln('<comment>No YAML files found.</comment>');
            return Command::SUCCESS;
        }

        // Load language data & flatten
        $flattenedPerLocale = [];
        foreach ($files as $file) {
            $localeRaw = self::inferLocale($file);            // e.g. "en", "pt-BR", "zh_Hant"
            $locale    = self::normalizeIso639_1($localeRaw); // → "en", "pt", "zh"
            $data      = Yaml::parseFile($inDir . '/' . $file);
            if (!is_array($data)) {
                $data = [];
            }
            $flat = [];
            self::flatten($data, '', $flat); // strips "|..." suffixes in keys
            ksort($flat, SORT_STRING);
            $flattenedPerLocale[$locale] = $flat; // later file overwrites earlier one for the same locale
        }

        // Normalize base language (if given)
        $baseLocale = $baseLocale !== '' ? self::normalizeIso639_1($baseLocale) : '';

        // Auto-base: if no base specified, but "en" exists → base = en
        if ($baseLocale === '' && isset($flattenedPerLocale['en'])) {
            $baseLocale = 'en';
        }

        // Case 1: No base language resolvable → standalone output (<source> from each file), filename <iso>.locallang.xlf
        if ($baseLocale === '') {
            foreach ($flattenedPerLocale as $locale => $pairs) {
                $doc = self::createXliff12($locale, $productName, $original);
                self::appendUnits($doc, $pairs); // only <source>
                $filePath = "$outDir/$locale.locallang.xlf";
                $doc->save($filePath);
                $output->writeln("<info>Writing $filePath</info>");
            }
            return Command::SUCCESS;
        }

        // Case 2: Base workflow: base → locallang.xlf (only <source>), targets → <iso>.locallang.xlf (<source> from base + <target> from respective language)
        if (!isset($flattenedPerLocale[$baseLocale])) {
            $output->writeln("<error>Base language '$baseLocale' not found.</error>");
            return Command::FAILURE;
        }

        $basePairs = $flattenedPerLocale[$baseLocale];

        // Base file
        $baseDoc = self::createXliff12($baseLocale, $productName, $original);
        self::appendUnits($baseDoc, $basePairs);
        $basePath = "$outDir/locallang.xlf";
        $baseDoc->save($basePath);
        $output->writeln("<info>Writing $basePath</info>");

        // Target languages
        foreach ($flattenedPerLocale as $locale => $pairs) {
            if ($locale === $baseLocale) {
                continue;
            }
            $doc = self::createXliff12($locale, $productName, $original);

            // Stable ID order based on base; target only set if available
            $orderedTargets = array_fill_keys(array_keys($basePairs), null);
            foreach ($pairs as $k => $v) {
                $orderedTargets[$k] = $v;
            }

            self::appendUnitsWithBase($doc, $basePairs, $orderedTargets);
            $filePath = "$outDir/$locale.locallang.xlf";
            $doc->save($filePath);
            $output->writeln("<info>Writing $filePath</info>");
        }

        return Command::SUCCESS;
    }

    private static function inferLocale(string $filename): string
    {
        // Expects names like "en.yml", "pt-BR.yaml", "zh_Hant.yml"
        return pathinfo($filename, PATHINFO_FILENAME);
    }

    /**
     * Reduce to primary ISO-639-1 code (2 letters, lowercase).
     * Examples: "pt-BR" -> "pt", "zh_Hant" -> "zh", "EN" -> "en".
     */
    private static function normalizeIso639_1(string $locale): string
    {
        $primary = preg_split('/[-_]/', $locale)[0] ?? $locale;
        $primary = strtolower(preg_replace('/[^a-zA-Z]/', '', $primary) ?? '');
        return substr($primary, 0, 2) ?: 'xx';
    }

    /**
     * Flatten nested arrays into "dot.notation" keys.
     * Internal suffixes like "|capitalize" are removed.
     *
     * @param array<string,mixed> $data
     * @param array<string,string> $out
     */
    private static function flatten(array $data, string $prefix, array &$out): void
    {
        foreach ($data as $key => $value) {
            $segment = $key;

            // Remove internal annotations like "|capitalize"
            if (str_contains($segment, '|')) {
                $segment = explode('|', $segment, 2)[0];
            }

            $id = $prefix === '' ? $segment : $prefix . '.' . $segment;

            if (is_array($value)) {
                self::flatten($value, $id, $out);
            } else {
                $out[$id] = (string)$value;
            }
        }
    }

    /**
     * @throws DOMException
     */
    private static function createXliff12(string $targetLanguage, string $productName, string $original): DOMDocument
    {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $xliff = $doc->createElement('xliff');
        $xliff->setAttribute('version', '1.2');
        $xliff->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:1.2');
        $doc->appendChild($xliff);

        $file = $doc->createElement('file');
        $file->setAttribute('datatype', 'plaintext');
        $file->setAttribute('original', $original);

        // Convention: always "en" as source; target = current language (also for en)
        $file->setAttribute('source-language', 'en');
        $file->setAttribute('target-language', $targetLanguage);

        $file->setAttribute('product-name', $productName);
        $xliff->appendChild($file);

        $body = $doc->createElement('body');
        $file->appendChild($body);

        return $doc;
    }

    /**
     * Append trans-units to a DOMDocument.
     *
     * @param array<string,string> $pairs
     * @throws DOMException
     */
    private static function appendUnits(DOMDocument $doc, array $pairs): void
    {
        /** @var DOMElement $body */
        $body = $doc->getElementsByTagName('body')->item(0);
        foreach ($pairs as $id => $text) {
            $transUnit = $doc->createElement('trans-unit');
            $transUnit->setAttribute('id', $id);

            $source = $doc->createElement('source');
            $source->appendChild($doc->createTextNode($text));
            $transUnit->appendChild($source);

            $body->appendChild($transUnit);
        }
    }

    /**
     * Build target language files based on base sources.
     *
     * @param array<string,string> $basePairs
     * @param array<string,string|null> $targetPairs
     * @throws DOMException
     */
    private static function appendUnitsWithBase(DOMDocument $doc, array $basePairs, array $targetPairs): void
    {
        /** @var DOMElement $body */
        $body = $doc->getElementsByTagName('body')->item(0);
        foreach ($basePairs as $id => $sourceText) {
            $transUnit = $doc->createElement('trans-unit');
            $transUnit->setAttribute('id', $id);

            $source = $doc->createElement('source');
            $source->appendChild($doc->createTextNode($sourceText));
            $transUnit->appendChild($source);

            $target = $doc->createElement('target');
            $targetText = $targetPairs[$id] ?? '';
            if ($targetText !== null && $targetText !== '') {
                $target->appendChild($doc->createTextNode($targetText));
            }
            $transUnit->appendChild($target);

            $body->appendChild($transUnit);
        }
    }
}
