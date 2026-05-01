..  include:: /Includes.rst.txt
..  highlight:: typoscript
..  index::
    TypoScript; Setup
..  _configuration-typoscript-setup:

Setup
=====

TypoScript setup is loaded by the :ref:`Site Set <configuration-site-set>` and
by the :ref:`Static TypoScript Include <configuration-typoscript>`. It
registers frontend assets, Fluid paths, the :ref:`contextual consent
<features-contextual-consent>` layout integration, and selected runtime
settings.

Service filtering
=================

..  confval:: services.whitelist

    :type: string (comma-separated list)
    :Default: ''
    :Path: plugin.tx_klaroconsentmanager.settings

    Restrict the available services to the given list of service *names*.
    If a whitelist is defined, **only** the listed services are active.
    Any configured :confval:`services.blacklist` is ignored when a whitelist is present.

    **Example:**

    ..  code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.services.whitelist = google-analytics, matomo


..  confval:: services.blacklist

    :type: string (comma-separated list)
    :Default: ''
    :Path: plugin.tx_klaroconsentmanager.settings

    Disable specific services by *name*. All other services remain active.
    The blacklist is evaluated **only if no** :confval:`services.whitelist` **is set**.

    **Example:**

    ..  code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.services.blacklist = facebook-pixel


..  note::

    **Priority:** Whitelist > Blacklist > Default

    - If a **whitelist** is set (non-empty), only its entries are active; a blacklist is ignored.
    - If **no whitelist** is set, the **blacklist** excludes its entries.
    - If **neither** is set, **all services are active**.

    This mechanism is especially useful in combination with TypoScript
    conditions, for example to enable or disable specific services depending
    on the current language, site identifier, or application context.
    See also the feature overview for :ref:`features-service-filtering`.

    **Example:**

    ..  code-block:: typoscript

        # Restrict the services exclusively to Google Analytics and Matomo if this is the default language
        [siteLanguage("languageId") === 0]
        plugin.tx_klaroconsentmanager.settings.services.whitelist = google-analytics, matomo
        [end]

        # Remove the Facebook Pixel services if the current language has the UID 1
        [siteLanguage("languageId") === 1]
        plugin.tx_klaroconsentmanager.settings.services.blacklist = facebook-pixel
        [end]

Configuration overrides
=======================

..  confval:: configuration.[...]

    :type: Array
    :Default: []
    :Path: plugin.tx_klaroconsentmanager.settings

    ..  warning::

        Experimental feature!

    The entries made here from TypoScript are merged into the configuration
    array shortly before the transformation into the JavaScript configuration.
    Use the lowerCamelCase notation of the final Klaro JavaScript keys.

    Nesting in the TypoScript override cannot currently be mapped reliably for
    list-like values such as services without an associative index. There is no
    plausibility check for the keys used.

    There is currently no type conversion based on the Klaro properties. Use
    only known, non-nested keys and string values unless you have verified the
    generated JavaScript configuration.

    **Example**

    ..  code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.configuration {
            elementID = overwrittenID
            cookieName = overwrittenCookieName
        }

..  _configuration-typoscript-setup-klaro-is-active:

TypoScript condition
====================

The extension registers the TypoScript condition variable `klaroIsActive`.
It returns `true` when a Klaro configuration is active for the current request.
The feature overview contains a more complete integration example:
:ref:`klaroIsActive`.

..  code-block:: typoscript

    [klaroIsActive]
    page.10 = TEXT
    page.10.value = Klaro is active
    [end]

..  _configuration-typoscript-setup-klaro-is-active-viewhelper:

Fluid ViewHelper
================

The Fluid namespace `klaro` is registered globally. Use
`{klaro:isActive()}` to branch Fluid output depending on the active Klaro
configuration.
The feature overview contains a template example:
:ref:`klaroIsActiveViewHelper`.

..  code-block:: html

    <f:if condition="{klaro:isActive()}">
        <f:then>
            Klaro is active.
        </f:then>
        <f:else>
            Klaro is inactive.
        </f:else>
    </f:if>
