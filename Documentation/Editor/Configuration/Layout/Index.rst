..  include:: /Includes.rst.txt


..  _for-editors-configuration-layout:

=======
Layout
=======


..  figure:: /Images/Configuration-Layout.png

    Klaro Configuration - Layout Tab

.. contents::
   :local:

Layout settings
===============

Color Scheme
------------

..  confval:: color_scheme

    :type: select
    :Default: 'dark_neutral'

    Default color scheme used for the display. The colors can also be overridden later via CSS variables.

    **Options:**

    *   Dark (neutral) `[dark_neutral]`
    *   Light (neutral) `[light_neutral]`
    *   Dark `[dark]`
    *   Light `[light]`

Alignment
---------

..  confval:: alignment

    :type: string
    :Default: ''

    **Condition:** `must_consent == FALSE && notice_as_modal == FALSE`

    Determines the alignment of the Consent box if `mustConsent` and `noticeAsModal` are disabled.

Default consent status
======================

Default
-------

..  confval:: default

    :type: boolean
    :Default: false

    Defines the default state for services in the consent modal (true=enabled by default). If this setting is set to "false", you can override it in any service.

Accept all
----------

..  confval:: accept_all

    :type: boolean
    :Default: true

    Setting `acceptAll` to `true` will show an "accept all" button in the notice and modal, which will enable all third-party services if the user clicks on it. If set to "false", there will be an "accept" button that will only enable the services that are enabled in the consent modal.

Behavior
========

Must consent
------------

..  confval:: must_consent

    :type: boolean
    :Default: false

    **Condition:** `notice_as_modal == FALSE`

    If `mustConsent` is set to true, Klaro will directly display the consent manager modal and not allow the user to close it before having actively consented or declines the use of third-party services.

Notice As Modal
---------------

..  confval:: notice_as_modal

    :type: boolean
    :Default: false

    **Condition:** `must_consent == FALSE`

    Show cookie notice as modal

Show title in consent notice
----------------------------

..  confval:: show_notice_title

    :type: boolean
    :Default: false

    **Condition:** `must_consent == FALSE`

    When enabled, Klaro shows the notice title.

..  _configuration-layout-htmltexts:

HTML Texts
----------

..  confval:: html_texts

    :type: boolean
    :Default: true

    If set to `true`, Klaro will render the texts given in the `consentModal.description` and `consentNotice.description` translations as HTML. This enables you to e.g. add custom links or interactive content. If you want to be able to output HTML tags via fluid-enriched labels (see "Content" → "Translations"), this option must be enabled.

Embedded
--------

..  confval:: embedded

    :type: boolean
    :Default: false

    Setting "embedded" to true will render the Klaro modal and notice without the modal background, allowing you to e.g. embed them into a specific element of your website, such as your privacy notice.

Testing
-------

..  confval:: testing

    :type: boolean
    :Default: false

    Setting `testing` to `true` will cause Klaro to not show the consent notice or modal by default, except if a special hashtag is appended to the URL (#klaro-testing). This makes it possible to test Klaro on your live website without affecting normal visitors.

No Auto-Load
------------

..  confval:: no_auto_load

    :type: boolean
    :Default: false

    Setting this to true will keep Klaro from automatically loading itself when the page is being loaded.

Other display settings
======================

Group by purpose
----------------

..  confval:: group_by_purpose

    :type: boolean
    :Default: true

    You can group services by their purpose in the modal. This is advisable if you have a large number of services. Users can then enable or disable entire groups of services instead of having to enable or disable every service.

Disable Powered By
------------------

..  confval:: disable_powered_by

    :type: boolean
    :Default: false

    You can also remove the "Realized with Klaro!" text in the consent modal. Please don't do this! We provide Klaro as a free open source tool. Placing a link to our website helps us spread the word about it, which ultimately enables us to make Klaro! better for everyone. So please be fair and keep the link enabled. Thanks :)
