..  include:: /Includes.rst.txt

..  _compatibility:

=============
Compatibility
=============

This extension is maintained for TYPO3 v13 and TYPO3 v14. The local package
metadata is the authoritative source for the supported version range.

..  list-table::
    :header-rows: 1

    *   - Requirement
        - Composer package
        - TER metadata
    *   - TYPO3
        - `^13.0 || ^14.0`
        - `13.4.0-14.3.99`
    *   - PHP
        - `^8.2 || ^8.3 || ^8.4 || ^8.5`
        - `8.2.0-8.5.99`

TYPO3 v13 and v14 use the same extension code path. Site Sets, Site Settings,
PSR-15 middleware, PSR-14 event listeners, the Fluid ViewHelper namespace, and
the TypoScript condition provider are available in both supported TYPO3 major
versions.

Backend module names
====================

TYPO3 v14 renamed and regrouped several backend modules. The underlying
extension functionality is unchanged, but the navigation paths used in this
manual differ by TYPO3 version.

..  list-table::
    :header-rows: 1

    *   - Task
        - TYPO3 v13
        - TYPO3 v14
    *   - Edit site configuration and assign Site Sets
        - :guilabel:`Site Management` → :guilabel:`Sites`
        - :guilabel:`Sites` → :guilabel:`Setup`
    *   - Inspect or edit TypoScript records
        - :guilabel:`Site Management` → :guilabel:`TypoScript`
        - :guilabel:`Sites` → :guilabel:`TypoScript`
    *   - Create Klaro records
        - :guilabel:`Web` → :guilabel:`List`
        - :guilabel:`Content` → :guilabel:`Records`
    *   - Edit page content and contextual consent fields
        - :guilabel:`Web` → :guilabel:`Page`
        - :guilabel:`Content` → :guilabel:`Layout`
    *   - Compare and update database schema
        - :guilabel:`Admin Tools` → :guilabel:`Maintenance`
        - :guilabel:`System` → :guilabel:`Maintenance`
    *   - View or install extensions in classic mode
        - :guilabel:`Admin Tools` → :guilabel:`Extensions`
        - :guilabel:`System` → :guilabel:`Extensions`
    *   - Edit global extension configuration
        - :guilabel:`Admin Tools` → :guilabel:`Settings` → :guilabel:`Extension Configuration`
        - :guilabel:`System` → :guilabel:`Settings` → :guilabel:`Extension Configuration`

Site Sets and Site Settings
===========================

For TYPO3 v13 and v14, the recommended integration is the Site Set
`erhaweb/klaro-consent-manager`. It ships the extension TypoScript and typed
Site Settings from `Configuration/Sets/KlaroConsentManager/`.

Site Settings are stored in
`config/sites/<site-identifier>/settings.yaml`. In supported TYPO3 v13 and
TYPO3 v14 versions, the file uses flat map keys:

..  code-block:: yaml
    :caption: config/sites/<site-identifier>/settings.yaml

    plugin.tx_klaroconsentmanager.settings.contextualconsent.mainSectionOnly: false

Static TypoScript Include
=========================

The extension still registers a Static TypoScript Include for installations
that use TypoScript records. This is a legacy-compatible fallback and is useful
for migrated projects that have not yet moved their configuration to Site Sets.

When Site Sets and TypoScript records are mixed in one site, make sure the
TypoScript record does not clear constants or setup from the Site Set.

Runtime features
================

The following features work the same way in TYPO3 v13 and TYPO3 v14:

* Backend FormEngine records for Klaro configurations, services, cookies, and
  contextual consent on content elements.
* Automatic frontend asset registration through TYPO3's AssetCollector with
  CSP nonce support.
* Standalone configuration output through the PSR-15 middleware at the globally
  configured path, by default `/klaro-config.js`.
* CSP-safe trigger link replacement through
  `ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput`.
* TypoScript condition variable `klaroIsActive`.
* Fluid ViewHelper `{klaro:isActive()}` using the registered `klaro` namespace.
* TypoScript service filtering through `services.whitelist` and
  `services.blacklist`.

Version-specific documentation
==============================

When a backend workflow differs by TYPO3 major version, this documentation
uses the module names listed above. Field names, TCA labels, TypoScript
settings, Site Settings keys, middleware identifiers, ViewHelper usage, and
record workflows are otherwise identical for the supported TYPO3 v13/v14 range.
