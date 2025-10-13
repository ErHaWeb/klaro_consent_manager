..  include:: /Includes.rst.txt
..  highlight:: typoscript
..  index::
    TypoScript; Setup
..  _configuration-typoscript-setup:

Setup
=====

Service Filtering
-----------------

..  confval:: services.whitelist

    :type: string (comma-separated list)
    :Default: ''

    Restrict the available services to the given list of service *names*.
    If a whitelist is defined, **only** the listed services are active.
    Any configured :confval:`services.blacklist` is ignored when a whitelist is present.

    **Example:**

    .. code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.services.whitelist = google-analytics, matomo


..  confval:: services.blacklist

    :type: string (comma-separated list)
    :Default: ''

    Disable specific services by *name*. All other services remain active.
    The blacklist is evaluated **only if no** :confval:`services.whitelist` **is set**.

    **Example:**

    .. code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.services.blacklist = facebook-pixel


..  note::

    **Priority:** Whitelist > Blacklist > Default

    - If a **whitelist** is set (non-empty), only its entries are active; a blacklist is ignored.
    - If **no whitelist** is set, the **blacklist** excludes its entries.
    - If **neither** is set, **all services are active**.

    This mechanism is especially useful in combination with TypoScript
    conditions, for example to enable or disable specific services depending
    on the current language, site identifier, or application context.

    **Example:**

    .. code-block:: typoscript

        # Restrict the services exclusively to Google Analytics and Matomo if this is the default language
        [siteLanguage("languageId") === 0]
        plugin.tx_klaroconsentmanager.settings.services.whitelist = google-analytics, matomo
        [end]

        # Remove the Facebook Pixel services if the current language has the UID 1
        [siteLanguage("languageId") === 1]
        plugin.tx_klaroconsentmanager.settings.services.blacklist = facebook-pixel
        [end]

Configuration
~~~~~~~~~~~~~

..  confval:: configuration.[...]

    :type: Array
    :Default: []
    :Path: plugin.tx_klaroconsentmanager.settings

    ..  warning::

        Experimental feature!

    The entries made here from TypoScript are merged into the configuration array shortly before the transformation into the JavaScript configuration. Accordingly, make sure that the lowerCamelCase notation is used to match the final keys.

    Please note that nesting in the TypoScript override cannot currently be mapped well in the context of elements such as services without an associative index. In addition, there is (still) no plausibility check of the keys used.

    In addition, there is currently no type conversion according to the properties, which can lead to problems on the Klaro JavaScript side with non-string types. At the moment, I recommend using only known keys without nesting and with string type.

    **Example**

    ..  code-block:: typoscript

        plugin.tx_klaroconsentmanager.settings.configuration {
            elementID = overwrittenID
            cookieName = overwrittenCookieName
        }
