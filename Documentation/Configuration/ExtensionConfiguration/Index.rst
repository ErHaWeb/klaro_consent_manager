..  include:: /Includes.rst.txt

..  _configuration-extension-configuration:

==============================
Global Extension Configuration
==============================

The global extension configuration is defined in `ext_conf_template.txt`.
It is independent of the site-specific Klaro configuration record and applies
to the frontend middleware of the extension.

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
    consent manager.

..  confval:: replaceUrl.reset

    :type: string
    :Default: `https://KLARO_RESET.com`

    Frontend links with this exact `href` value are replaced by the
    `ReplaceBeforeOutput` middleware with a Klaro trigger link that resets the
    consent state and opens the consent manager.

..  confval:: klaroConfigurationPath

    :type: string
    :Default: `/klaro-config.js`

    If this value is not empty, the `KlaroConfiguration` middleware exposes the
    active Klaro configuration as JavaScript at this path. The default endpoint
    is `/klaro-config.js`.

    This endpoint is useful when the Klaro configuration managed in TYPO3
    should be consumed by another application.
