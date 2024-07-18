..  include:: /Includes.rst.txt
..  highlight:: typoscript

..  index::
    TypoScript; Constants
..  _configuration-typoscript-constants:

Constants
=========

Constants (or in the Site Sets context called Site Settings) that influence the behaviour of the Klaro extension are described below.

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

    In addition to the default path `EXT:klaro_consent_manager/Resources/Private/Templates/`,
    this constant can be used to define a custom template root path to overwrite
    individual fluid files as needed.

..  _configuration-typoscript-constants-view-partialrootpath:

Partial root path
~~~~~~~~~~~~~~~~~

..  confval:: partialRootPath

    :type: string
    :Default: empty
    :Path: plugin.tx_klaroconsentmanager.view

    In addition to the default path `EXT:klaro_consent_manager/Resources/Private/Partials/`,
    this constant can be used to define a custom partial root path to overwrite
    individual fluid files as needed.

..  _configuration-typoscript-constants-view-layoutrootpath:

Layout root path
~~~~~~~~~~~~~~~~~

..  confval:: layoutRootPath

    :type: string
    :Default: empty
    :Path: plugin.tx_klaroconsentmanager.view

    In addition to the default path `EXT:klaro_consent_manager/Resources/Private/Layouts/`,
    this constant can be used to define a custom layout root path to overwrite
    individual fluid files as needed.

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

Custom additions for Klaro CSS
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

..  confval:: css.klaro-custom:

    :type: string
    :Default: EXT:klaro_consent_manager/Resources/Public/Css/klaro-custom.min.css
    :Path: plugin.tx_klaroconsentmanager.settings

Klaro JavaScript
~~~~~~~~~~~~~~~~

..  confval:: javascript.klaro-default

    :type: string
    :Default: EXT:klaro_consent_manager/Resources/Public/JavaScript/klaro-no-translations-no-css.js
    :Path: plugin.tx_klaroconsentmanager.settings

Replace attributes
~~~~~~~~~~~~~~~~~~

..  confval:: contextualconsent.replaceAttributes

    :type: string
    :Default: src,href
    :Path: plugin.tx_klaroconsentmanager.settings

    Comma-separated list of attributes that should be automatically replaced within the content element of the contextual consent

Main section only
~~~~~~~~~~~~~~~~~

..  confval:: contextualconsent.mainSectionOnly

    :type: boolean
    :Default: true
    :Path: plugin.tx_klaroconsentmanager.settings

    Activate this option if the contextual consent box should only be limited to the main content (without heading and footer).

Disable Klaro Consent Management
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

..  confval:: configuration.disabled

    :type: boolean
    :Default: false
    :Path: plugin.tx_klaroconsentmanager.settings
