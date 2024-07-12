..  include:: /Includes.rst.txt


..  _for-editors-configuration-advanced:

========
Advanced
========

.. contents::
   :local:

Base Configuration
==================

Config Variable Name
--------------------

..  confval:: config_variable_name

    :type: string
    :Default: ''

    By default, Klaro loads the configuration from a global variable `klaroConfig`. You can change this here. Specifying this field will cause the attribute data-klaro-config="yourConfigVariableName" to be set.

Element ID
----------

..  confval:: element_i_d

    :type: string
    :Default: ''

    You can customize the ID of the `div` element that Klaro will create when starting up. By default, Klaro will use "klaro".

Additional Class
----------------

..  confval:: additional_class

    :type: string
    :Default: ''

    You can specify an additional class (or classes) that will be added to the Klaro `div`

Storage
=======

Storage Method
--------------

..  confval:: storage_method

    :type: select
    :Default: 'cookie'

    You can customize how Klaro persists consent information in the browser. Specify either `cookie` (the default) or `localStorage`.

    **Options:**

    *   None `[none]`
    *   Cookie (default) `[cookie]`
    *   Local Storage `[localStorage]`

Storage Name
------------

..  confval:: storage_name

    :type: string
    :Default: 'klaro'

    You can customize the name of the cookie or localStorage entry that Klaro will use for storing the consent information. By default, Klaro will use "klaro".

Callback
========

Callback
--------

..  confval:: callback

    :type: string
    :Default: ''

    You can define an optional callback function that will be called each time the consent state for any given service changes. The consent value will be passed as the first parameter to the function (true=consented). The `service` config will be passed as the second parameter. The content of this field is wrapped with `callback: function(consent, service) { }`
