..  include:: /Includes.rst.txt
..  highlight:: typoscript
..  index::
    TypoScript; Setup
..  _configuration-typoscript-setup:

Setup
=====

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
