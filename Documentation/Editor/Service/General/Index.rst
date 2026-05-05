..  include:: /Includes.rst.txt


..  _for-editors-service-general:

=======
General
=======

..  figure:: /Images/Service-General.png
    :class: with-shadow
    :alt: General tab of a Klaro service record

    Service Configuration - General Tab

..  contents::
   :local:

General Settings
================

Title
-----

..  confval:: title
    :name: service-title

    :type: string with value picker
    :Default: ''

    The title defined here is not mandatory and is intended for backend display
    only. The field is a free input field with presets in the value picker.
    See :ref:`features-service-presets` for the feature overview.

    **Options:**

    *   Adobe Fonts
    *   Google AdSense
    *   Surveillance Camera
    *   Career Captain
    *   Microsoft Clarity
    *   Cloudflare
    *   External Tracker
    *   Facebook Pixel
    *   Fast Fonts
    *   Google Ads
    *   Google Analytics
    *   Google Fonts
    *   Google Maps
    *   Google Tag Manager
    *   Inline Tracker
    *   Instagram
    *   Intercom
    *   Klaro!
    *   Matomo/Piwik
    *   Mouseflow
    *   SalesViewer
    *   TikTok
    *   X (formerly Twitter)
    *   Userlike
    *   Vimeo
    *   YouTube

Default
-------

..  confval:: default
    :name: service-default

    :type: boolean
    :Default: false

    If `default` is set to `true`, the service will be enabled by default. This overrides the global `default` setting (if this is set to "false").

Name
----

..  confval:: name

    :type: string with value picker
    :Default: ''

    Each service must have a unique name. Klaro looks for HTML elements with a
    matching `data-name` attribute to identify elements that belong to this
    service. The field is a required free input field with presets in the value
    picker.

    **Options:**

    *   Adobe Fonts `[adobe-fonts]`
    *   Google AdSense `[adsense]`
    *   Surveillance Camera `[camera]`
    *   Career Captain `[career-captain]`
    *   Microsoft Clarity `[clarity]`
    *   Cloudflare `[cloudflare]`
    *   External Tracker `[external-tracker]`
    *   Facebook Pixel `[facebook-pixel]`
    *   Fast Fonts `[fast-fonts]`
    *   Google Ads `[google-ads]`
    *   Google Analytics `[google-analytics]`
    *   Google Fonts `[google-fonts]`
    *   Google Maps `[google-maps]`
    *   Google Tag Manager `[google-tag-manager]`
    *   Inline Tracker `[inline-tracker]`
    *   Instagram `[instagram]`
    *   Intercom `[intercom]`
    *   Klaro! `[klaro]`
    *   Matomo/Piwik `[matomo]`
    *   Mouseflow `[mouseflow]`
    *   SalesViewer `[salesviewer]`
    *   TikTok `[tiktok]`
    *   X (formerly Twitter) `[twitter]`
    *   Userlike `[userlike]`
    *   Vimeo `[vimeo]`
    *   YouTube `[youtube]`

Purposes
--------

..  confval:: purposes

    :type: select (multiple)
    :Default: ''

    The purpose(s) of this service that will be listed on the consent notice.
    Do not forget to add translations for all purposes you list here. Custom
    purpose options are documented in :ref:`howto-custom-purpose`; missing
    labels are explained in :ref:`faq-missing-translation`.

    **Options:**

    *   **Option Group: Custom**

        *   Any custom option defined in TSconfig ...

    *   **Option Group: Additional**

        *   Analytics `[analytics]`
        *   Security `[security]`
        *   Live Chat `[livechat]`
        *   Styling `[styling]`
        *   Videos `[videos]`
        *   Social Media `[social]`
        *   Miscellaneous `[misc]`

    *   **Option Group: Default**

        *   Functional `[functional]`
        *   Performance `[performance]`
        *   Marketing `[marketing]`
        *   Advertising `[advertising]`
