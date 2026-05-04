..  include:: /Includes.rst.txt

..  _configuration-extension-configuration:

==============================
Global Extension Configuration
==============================

The global extension configuration is defined in `ext_conf_template.txt`.
It is independent of the site-specific Klaro configuration record and applies
to global frontend integration details of the extension.

Backend path
============

..  list-table::
    :header-rows: 1

    *   - TYPO3 v13
        - TYPO3 v14
    *   - :guilabel:`Admin Tools` → :guilabel:`Settings` → :guilabel:`Extension Configuration`
        - :guilabel:`System` → :guilabel:`Settings` → :guilabel:`Extension Configuration`

Settings
========

..  confval:: replaceUrl.show

    :type: string
    :Default: `https://KLARO_CONSENT.com`

    Frontend links with this exact `href` value are replaced by the
    `ReplaceBeforeOutput` middleware with a Klaro trigger link that opens the
    consent manager. See :ref:`features-trigger-links` for the feature
    overview.

..  confval:: replaceUrl.reset

    :type: string
    :Default: `https://KLARO_RESET.com`

    Frontend links with this exact `href` value are replaced by the
    `ReplaceBeforeOutput` middleware with a Klaro trigger link that resets the
    consent state and opens the consent manager. See
    :ref:`features-trigger-links` for the feature overview.

..  confval:: klaroConfigurationPath

    :type: string
    :Default: `/klaro-config.js`

    If this value is not empty, the active Klaro configuration is exposed as
    JavaScript at this path and loaded before the Klaro JavaScript library. The
    default endpoint is `/klaro-config.js`. See
    :ref:`features-standalone-configuration` for the feature overview.

    This endpoint is useful when the Klaro configuration managed in TYPO3
    should be consumed by another application. It can also be set to an empty
    value to use the backwards-compatible inline configuration fallback.

    After changing this value, flush TYPO3's system caches so the generated
    site routing configuration is rebuilt.
