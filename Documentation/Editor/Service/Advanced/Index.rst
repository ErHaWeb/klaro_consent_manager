..  include:: /Includes.rst.txt


..  _for-editors-service-advanced:

========
Advanced
========

..  figure:: /Images/Service-Advanced.png

    Service Configuration - Advanced Tab

.. contents::
   :local:

Advanced Settings
==================

Callback
-----

..  confval:: callback

    :type: string
    :Default: ''

    You can define an optional callback function that will be called each time the consent state for the given service changes. The consent value will be passed as the first parameter to the function (true=consented). The `service` config will be passed as the second parameter. The content of this field is wrapped with `callback: function(consent, service) { }`. `consent` is a bool that reflects the current state. service` holds all information about the currently selected service. When you enter something here, you need to know what you are doing. It is your responsibility that no JavaScript errors occur.

`onAccept` callback function
-----

..  confval:: on_accept

    :type: string
    :Default: ''

    Here you can define JavaScript code that will be called each time the service was accepted. The content of this field is wrapped with `callback: function(handlerOpts) { }`. The object `handlerOpts` contains the objects `config` (the current Klaro! configuration), `service` (the current service) and possibly `vars` (any values defined by object notation in the vars field in the backend). When you enter something here, you need to know what you are doing. It is your responsibility that no JavaScript errors occur.

`onInit` callback function
-----

..  confval:: on_init

    :type: string
    :Default: ''

    Here you can define JavaScript code that will be called once per page-load. The content of this field is wrapped with `callback: function(handlerOpts) { }`. The object `handlerOpts` contains the objects `config` (the current Klaro! configuration), `service` (the current service) and possibly `vars` (any values defined by object notation in the vars field in the backend). When you enter something here, you need to know what you are doing. It is your responsibility that no JavaScript errors occur.

`onDecline` callback function
-----

..  confval:: on_decline

    :type: string
    :Default: ''

    Here you can define JavaScript code that will be called each time the service was declined. The content of this field is wrapped with `callback: function(handlerOpts) { }`. The object `handlerOpts` contains the objects `config` (the current Klaro! configuration), `service` (the current service) and possibly `vars` (any values defined by object notation in the vars field in the backend). When you enter something here, you need to know what you are doing. It is your responsibility that no JavaScript errors occur.

Variables
-----

..  confval:: vars

    :type: string
    :Default: ''

    Variables that can be used in callback functions. The content of this field is enclosed in curly brackets. The JavaScript object notation is expected in them. When you enter something here, you need to know what you are doing. It is your responsibility that no JavaScript errors occur.
