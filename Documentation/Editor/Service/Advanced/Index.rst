..  include:: /Includes.rst.txt


..  _for-editors-service-advanced:

========
Advanced
========

..  figure:: /Images/Service-Advanced.png
    :alt: Advanced tab of a Klaro service record with JavaScript callback fields

    Service Configuration - Advanced Tab

..  contents::
   :local:

Advanced Settings
==================

Callback
--------

..  confval:: callback

    :type: string
    :Default: ''

    You can define an optional callback function that will be called each time
    the consent state for the given service changes. The consent value is passed
    as the first parameter (`true` means consented). The `service` config is
    passed as the second parameter. The content of this field is wrapped with
    `callback: function(consent, service) { }`. `consent` is a boolean that
    reflects the current state. `service` holds all information about the
    selected service.

`onAccept` callback function
----------------------------

..  confval:: on_accept

    :type: string
    :Default: ''

    JavaScript code that is called each time the service is accepted. The
    content of this field is wrapped with
    `callback: function(handlerOpts) { }`. The object `handlerOpts` contains
    `config` (the current Klaro configuration), `service` (the current service),
    and `vars` when values were defined in the `vars` field.

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Tag Manager**

        // we notify the tag manager about all services that were accepted. You can define
        // a custom event in GTM to load the service if consent was given.
        for(let k of Object.keys(handlerOpts.consents)){
            if (handlerOpts.consents[k]){
                let eventName = 'klaro-'+k+'-accepted'
                dataLayer.push({'event': eventName})
            }
        }

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Analytics**

        // we grant analytics storage
        gtag('consent', 'update', {
            'analytics_storage': 'granted',
        })

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Ads**

        // we grant ad storage and personalization
        gtag('consent', 'update', {
            'ad_storage': 'granted',
            'ad_user_data': 'granted',
            'ad_personalization': 'granted'
        })

`onInit` callback function
--------------------------

..  confval:: on_init

    :type: string
    :Default: ''

    JavaScript code that is called once per page load. The content of this
    field is wrapped with `callback: function(handlerOpts) { }`. The object
    `handlerOpts` contains `config` (the current Klaro configuration), `service`
    (the current service), and `vars` when values were defined in the `vars`
    field.

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Tag Manager**

        // initialization code here (will be executed only once per page-load)
        window.dataLayer = window.dataLayer || [];
        window.gtag = function(){dataLayer.push(arguments)}
        gtag('consent', 'default', {'ad_storage': 'denied', 'analytics_storage': 'denied', 'ad_user_data': 'denied', 'ad_personalization': 'denied'})
        gtag('set', 'ads_data_redaction', true)

`onDecline` callback function
-----------------------------

..  confval:: on_decline

    :type: string
    :Default: ''

    JavaScript code that is called each time the service is declined. The
    content of this field is wrapped with
    `callback: function(handlerOpts) { }`. The object `handlerOpts` contains
    `config` (the current Klaro configuration), `service` (the current service),
    and `vars` when values were defined in the `vars` field.

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Analytics**

        // we deny analytics storage
        gtag('consent', 'update', {
            'analytics_storage': 'denied',
        })

    ..  code-block:: javascript
        :linenos:
        :caption: **Example: Google Ads**

        // we decline ad storage and personalization
        gtag('consent', 'update', {
            'ad_storage': 'denied',
            'ad_user_data': 'denied',
            'ad_personalization': 'denied'
        })

Variables
---------

..  confval:: vars

    :type: string
    :Default: ''

    Variables that can be used in callback functions. The content of this field
    is enclosed in curly brackets and must be valid JavaScript object notation.

..  warning::

    Callback and variable fields are emitted to the frontend configuration.
    Enter only trusted JavaScript and validate syntax before deploying changes.
