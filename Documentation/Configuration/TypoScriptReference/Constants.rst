..  include:: /Includes.rst.txt
..  highlight:: typoscript

..  index::
    TypoScript; Constants
..  _configuration-typoscript-constants:

Constants
=========

These values are defined in
`Configuration/Sets/KlaroConsentManager/settings.definitions.yaml` and
`Configuration/Sets/KlaroConsentManager/constants.typoscript`.

With the recommended :ref:`Site Set integration <configuration-site-set>`,
override them as Site Settings in
`config/sites/<site-identifier>/settings.yaml` by using flat map keys:

..  code-block:: yaml

    plugin.tx_klaroconsentmanager.view.templateRootPath: 'EXT:sitepackage/Resources/Private/Templates/'

With the :ref:`Static TypoScript Include fallback <configuration-typoscript>`,
override the same values as classic TypoScript constants.

..  _configuration-typoscript-constants-view:

View
----

The following options are located under the following path:
:typoscript:`plugin.tx_klaroconsentmanager.view`

..  _configuration-typoscript-constants-view-templaterootpath:

Template root path
~~~~~~~~~~~~~~~~~~

..  confval:: templateRootPath

    :type: string
    :Default: empty
    :Path: plugin.tx_klaroconsentmanager.view

    Defaults to `EXT:klaro_consent_manager/Resources/Private/Templates/`.
    Override this path to replace individual frontend Fluid templates. See
    :ref:`howto-reference-fluid` for the backend-field alternative and expected
    directory structure.

..  _configuration-typoscript-constants-view-partialrootpath:

Partial root path
~~~~~~~~~~~~~~~~~

..  confval:: partialRootPath

    :type: string
    :Default: empty
    :Path: plugin.tx_klaroconsentmanager.view

    Defaults to `EXT:klaro_consent_manager/Resources/Private/Partials/`.
    Override this path to replace individual frontend Fluid partials.

..  _configuration-typoscript-constants-view-layoutrootpath:

Layout root path
~~~~~~~~~~~~~~~~~

..  confval:: layoutRootPath

    :type: string
    :Default: empty
    :Path: plugin.tx_klaroconsentmanager.view

    Defaults to `EXT:klaro_consent_manager/Resources/Private/Layouts/`.
    Override this path to replace frontend Fluid layouts.

..  _configuration-typoscript-constants-settings:

Settings
--------

The following options are located under the following path:
:typoscript:`plugin.tx_klaroconsentmanager.settings`

Klaro CSS
~~~~~~~~~

..  confval:: css.klaro-default

    :type: string
    :Default: EXT:klaro_consent_manager/Resources/Public/Css/klaro.min.css
    :Path: plugin.tx_klaroconsentmanager.settings

    CSS file registered for the Klaro frontend.

Custom additions for Klaro CSS
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

..  confval:: css.klaro-custom

    :type: string
    :Default: EXT:klaro_consent_manager/Resources/Public/Css/klaro-custom.min.css
    :Path: plugin.tx_klaroconsentmanager.settings

    Additional extension CSS with TYPO3-specific Klaro adjustments.

Klaro JavaScript
~~~~~~~~~~~~~~~~

..  confval:: javascript.klaro-default

    :type: string
    :Default: EXT:klaro_consent_manager/Resources/Public/JavaScript/klaro-no-translations-no-css.js
    :Path: plugin.tx_klaroconsentmanager.settings

    Self-hosted Klaro JavaScript file registered in the frontend.

Replace attributes
~~~~~~~~~~~~~~~~~~

..  confval:: contextualconsent.replaceAttributes

    :type: string (comma-separated list)
    :Default: src,href
    :Path: plugin.tx_klaroconsentmanager.settings

    Comma-separated list of attributes that are moved to matching
    `data-*` attributes in :ref:`contextual consent
    <features-contextual-consent>` content. The default moves `src` and `href`
    to `data-src` and `data-href`.

Main section only
~~~~~~~~~~~~~~~~~

..  confval:: contextualconsent.mainSectionOnly

    :type: boolean
    :Default: true
    :Path: plugin.tx_klaroconsentmanager.settings

    Activate this option if the :ref:`contextual consent
    <features-contextual-consent>` placeholder should be limited to the main
    content of the content element, without heading and footer.

Disable Klaro Consent Management
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

..  confval:: configuration.disabled

    :type: boolean
    :Default: false
    :Path: plugin.tx_klaroconsentmanager.settings

    Disable the frontend Klaro integration while keeping the extension
    installed and configured. This value is one of the prerequisites evaluated
    by :ref:`klaroIsActive`.
