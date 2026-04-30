..  include:: /Includes.rst.txt

..  _howto-yaml-to-xliff:

=================
YAML to XLIFF CLI
=================

The extension provides the Symfony command `klaro:yaml-to-xliff`. It converts
top-level Klaro translation YAML files into TYPO3-compatible XLIFF 1.2 files.

Usage
=====

..  code-block:: bash

    vendor/bin/typo3 klaro:yaml-to-xliff src/translations Resources/Private/Language --base=en

With DDEV:

..  code-block:: bash

    ddev typo3 klaro:yaml-to-xliff src/translations Resources/Private/Language --base=en

Arguments and options
=====================

..  confval:: input

    :type: directory

    Directory containing `*.yml` or `*.yaml` files. File names are interpreted
    as locale identifiers, for example `en.yml` or `de.yaml`.

..  confval:: output

    :type: directory

    Target directory for generated XLIFF files. The command creates the
    directory if it does not exist.

..  confval:: --base

    :type: string

    Base language. If the base language is `en`, the command writes
    `locallang.xlf` for the base language and `<iso>.locallang.xlf` for target
    languages.

..  confval:: --product-name

    :type: string
    :Default: `klaro_consent_manager`

    Optional `product-name` attribute for the generated XLIFF `<file>` tag.

..  confval:: --original

    :type: string
    :Default: `EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf`

    Optional `original` attribute for the generated XLIFF `<file>` tag.
