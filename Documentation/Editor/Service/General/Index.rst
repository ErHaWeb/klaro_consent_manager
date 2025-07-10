..  include:: /Includes.rst.txt


..  _for-editors-service-general:

=======
General
=======

..  figure:: /Images/Service-General.png

    Service Configuration - General Tab

.. contents::
   :local:

General Settings
=======

Title
-----

..  confval:: title

    :type: string/select
    :Default: ''

    The title defined here is not mandatory and is intended for backend display only.

    **Options:**

    *   Google AdSense
    *   Surveillance Camera
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
    *   Matomo/Piwik
    *   Mouseflow
    *   X (formerly Twitter)
    *   Userlike
    *   Vimeo
    *   YouTube

Default
-----

..  confval:: default

    :type: boolean
    :Default: false

    If `default` is set to `true`, the service will be enabled by default. This overrides the global `default` setting (if this is set to "false").

Name
-----

..  confval:: name

    :type: string/select
    :Default: ''

    Each service must have a unique name. Klaro will look for HTML elements with a matching `data-name` attribute to identify elements that belong to this service.

    **Options:**

    *   Google AdSense `[adsense]`
    *   Surveillance Camera `[camera]`
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
    *   Matomo/Piwik `[matomo]`
    *   Mouseflow `[mouseflow]`
    *   X (formerly Twitter) `[twitter]`
    *   Userlike `[userlike]`
    *   Vimeo `[vimeo]`
    *   YouTube `[youtube]`

Purposes
-----

..  confval:: purposes

    :type: select
    :Default: ''

    The purpose(s) of this service that will be listed on the consent notice. Do not forget to add translations for all purposes you list here.

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
