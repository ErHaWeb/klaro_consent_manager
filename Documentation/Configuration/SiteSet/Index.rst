..  include:: /Includes.rst.txt

..  _configuration-site-set:

======================
Site Set and Settings
======================

The recommended way to include this extension in TYPO3 v13 and TYPO3 v14 is
the Site Set `erhaweb/klaro-consent-manager`.

The Site Set is defined in `Configuration/Sets/KlaroConsentManager/config.yaml`
and provides the TypoScript setup, TypoScript constants, and typed Site
Settings from `Configuration/Sets/KlaroConsentManager/`.

Backend path
============

..  list-table::
    :header-rows: 1

    *   - TYPO3 v13
        - TYPO3 v14
    *   - :guilabel:`Site Management` → :guilabel:`Sites`
        - :guilabel:`Sites` → :guilabel:`Setup`

Open the site configuration, add the Site Set
`erhaweb/klaro-consent-manager`, and save the site configuration.

Override Site Settings
======================

Site Settings are stored in
`config/sites/<site-identifier>/settings.yaml`. Supported TYPO3 v13/v14
versions use flat map keys:

..  code-block:: yaml
    :caption: config/sites/<site-identifier>/settings.yaml

    plugin.tx_klaroconsentmanager.settings.contextualconsent.mainSectionOnly: false
    plugin.tx_klaroconsentmanager.settings.configuration.disabled: false

The available Site Settings are the same values that are documented as
:ref:`TypoScript constants <configuration-typoscript-constants>`.

Classic fallback
================

The extension also registers a Static TypoScript Include. Use it only for
projects that still configure frontend TypoScript through TypoScript records.
For new TYPO3 v13/v14 setups, use the Site Set.
