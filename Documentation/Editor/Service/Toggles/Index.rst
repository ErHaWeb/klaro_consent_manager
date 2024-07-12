..  include:: /Includes.rst.txt


..  _for-editors-service-toggles:

=======
Toggles
=======

..  figure:: /Images/Service-Toggles.png

    Service Configuration - Toggles Tab

.. contents::
   :local:

Toggles
===============

Required
-----

..  confval:: required

    :type: boolean
    :Default: false

    If `required` is set to `true`, Klaro will not allow this service to be disabled by the user. Use this for services that are always required for your website to function (e.g. shopping cart cookies).

Opt-Out
-----

..  confval:: opt_out

    :type: boolean
    :Default: false

    If `optOut` is set to `true`, Klaro will load this service even before the user has given explicit consent. We strongly advise against this.

Only Once
-----

..  confval:: only_once

    :type: boolean
    :Default: false

    If `onlyOnce` is set to `true`, the service will only be executed once regardless how often the user toggles it on and off. This is relevant e.g. for tracking scripts that would generate new page view events every time Klaro disables and re-enables them due to a consent change by the user.

Contextual Consent Only
-----

..  confval:: contextual_consent_only

    :type: boolean
    :Default: false

    Content can be excluded from general consent by setting contextualConsentOnly to true. In this case, the consent must be contextual.
