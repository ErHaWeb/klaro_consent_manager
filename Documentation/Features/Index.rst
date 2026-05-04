..  include:: /Includes.rst.txt

..  _features:

========
Features
========

This extension impresses with a variety of features that improve normal use of Klaro and stand out from other Klaro integrations. See for yourself on this page.

..  contents::
   :local:

..  _features-backend-gui:

Backend GUI
===========

Klaro is configured via a `separate JavaScript file <https://github.com/klaro-org/klaro-js/blob/master/dist/config.js>`__ in many web projects. That file is included alongside the Klaro JavaScript. Some TYPO3 extensions that are based on Klaro only provide a placeholder for a `config.js` file.

..  code-block:: javascript
    :caption: Example of a default `config.js` file from the Klaro repository

    var klaroConfig = {
        version: 1,
        elementID: "klaro",
        styling: {
            theme: ["light", "top", "wide"]
        },
        showDescriptionEmptyStore: !0,
        noAutoLoad: !1,
        htmlTexts: !0,
        embedded: !1,
        groupByPurpose: !0,
        autoFocus: !1,
        showNoticeTitle: !1,
        storageMethod: "cookie",
        cookieName: "klaro",
        cookieExpiresAfterDays: 365,
        ...

This extension now takes a new approach by making **all parameters without exceptions** provided by the Klaro configuration file **editable via the TYPO3 backend** based on the TYPO3 :ref:`FormEngine <t3coreapi:FormEngine>` in a beautiful GUI with nice titles and comprehensible descriptive texts. The individual backend fields are documented in the :ref:`editor configuration reference <for-editors-configuration>`.

..  image:: /Images/Backend-GUIExample.png
    :class: with-shadow
    :alt: TYPO3 backend form showing Klaro configuration fields

In this way, even editors without JavaScript syntax knowledge can get the best out of Klaro. It also ensures that there are no syntax errors and that the output is reduced to the essentials in terms of performance.

In the end, the Klaro configuration is generated dynamically based on the backend settings and automatically integrated in the desired JavaScript format at the right place in the front end of the website.

Mutually exclusive parameters
=============================

In the context of the backend GUI, the extension automatically checks whether the relevant parameters are mutually exclusive. The backend interface is customised depending on the current setting. This means you no longer have to deal with the context and can configure Klaro more intuitively.

..  note::

    **Example**

    Activating the "Must consent" setting `[mustConsent]`, for example, means that "Notice As Modal" `[noticeAsModal]` is no longer displayed as an option in the backend interface. Klaro uses a modal in this case, so the settings are mutually exclusive.

..  _features-neutral-color-scheme:

Neutral color scheme
====================

Apart from the default styling of Klaro, a color-neutral scheme is offered for selection. According to current case law, this is required, a point that has not yet been taken into account in Klaro's default CSS. In addition to this preset selection, the SCSS files supplied with this extension naturally also give you full freedom to customise the styling to suit your requirements.

..  _features-xliff-based-translations:

XLIFF-based translations
========================

The Klaro application ships labels for a variety of languages and selects them
in JavaScript. Klaro also provides the option of overwriting labels of defined
languages via configuration.

..  code-block:: javascript
    :caption: Example for the configuration of labels in `config.js`

    translations: {
        zz: {
            privacyPolicyUrl: "/#privacy"
        },
        de: {
            privacyPolicyUrl: "/#datenschutz",
            consentModal: {
                description: 'Hier k\xf6nnen Sie einsehen und anpassen, welche Information wir \xfcber Sie sammeln. Eintr\xe4ge die als "Beispiel" gekennzeichnet sind dienen lediglich zu Demonstrationszwecken und werden nicht wirklich verwendet.'
            },
            ...
        },
        en: {
            consentModal: {
                title: "<u>test</u>",
                description: 'Here you can see and customize the information that we collect about you. Entries marked as "Example" are just for demonstration purposes and are not really used on this website.'
            },
            ...
        }
    }

This has the disadvantage that considerably more translation code is loaded than is required at the time of the request in a particular language.

Fortunately, TYPO3 has already integrated a successful principle with XLIFF files with which labels can be integrated depending on the currently selected language. This is used here by the Klaro Extension. No labels are loaded from languages that are not currently needed.

All labels that Klaro otherwise supplies on the JavaScript side were transferred to this format and uploaded to the Crowdin translation server so that they can be maintained and expanded there by our active community.

The command `klaro:yaml-to-xliff` can convert Klaro YAML translations into
TYPO3-compatible XLIFF files. See :ref:`howto-yaml-to-xliff`.

..  note::
    From a technical perspective, these labels are ultimately delivered in the Klaro configuration under the global fallback index `zz`. The Klaro JavaScript knows no other source for labels than the one we provide through TYPO3. A more efficient way that combines the best of both worlds.

..  _features-fluid-enriched-labels:

Fluid enriched labels
=====================

This extension offers the possibility of enriching or replacing any label with the help of fluid if necessary. Sometimes you want to enrich labels based on fluid functionality to add more dynamics. For example, in a multi-site installation it would be possible to output a site-specific consent modal title.

This also simplifies the use of HTML in labels. Not only can you use the full range of functions of Fluid to achieve dynamic HTML output for description texts, but the necessary character encoding of the texts in JavaScript is also taken care of for you.

After setting the template paths, the labels as a whole can be replaced/modified with Fluid Content or Fluid Content service/purpose can be prepended/appended.

The full description of this feature can be found in the
:ref:`Fluid-enriched labels guide <howto-fluid-enriched-labels>`.

..  youtube:: NYb42MKC5hw

..  _features-service-presets:

Service presets
===============

Most common services that require consent, such as Google Analytics, Matomo, Facebook Pixel or YouTube, are already covered via preconfigured presets. In these cases, you only have to select the preset and you can use the description texts that have already been prepared. See :ref:`service general settings <for-editors-service-general>` for the backend fields that use these presets.

..  image:: /Images/Service-PresetSelection.png
    :alt: Service record value picker with predefined Klaro service presets

..  _features-cookie-info-table:

Cookie info table
=================

In addition to the description text of a service, all relevant cookie information is automatically displayed in a table. This is achieved by Klaro automatically enriching the `description` label of the service via the Fluid file `EXT:Resources/Private/Templates/Append/Service/Description.html`. The cookie records used for this table are documented in the :ref:`service cookies section <for-editors-service-cookies>`.

..  image:: /Images/Frontend-ServiceDescriptionExample.png
    :alt: Klaro service description with automatically appended cookie information table

..  _features-trigger-links:

Trigger Links
=============

Certain link targets can be used to create links that open the Klaro Consent Management settings (`https://KLARO_CONSENT.com`) and reset the settings (`https://KLARO_RESET.com`). The link targets are replaced with CSP-compliant JavaScript functionality in the final HTML output. The links are configurable via the :ref:`global extension configuration <configuration-extension-configuration>`. The middleware `ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput` is responsible for this.

..  youtube:: V4u4v0QS93s

..  note::

    Thanks to `Georg Ringer <https://www.studiomitte.com/ueber-uns/georg-ringer>`__ and Studio Mitte Digital Media GmbH for this feature idea and the permission to use it from their `Klaro integration <https://github.com/studiomitte/klaro>`__.

..  _features-contextual-consent:

Contextual Consent
==================

The Klaro Extension makes it possible to hide any content elements behind a consent requirement. Klaro’s contextual consent feature is used for this purpose. Editors configure the service on the content element as described in :ref:`for-editors-contextual-consent`. By default, the Fluid Styled Content Layout (`EXT:fluid_styled_content/Resources/Private/Layouts/Default.html`) is overwritten for this purpose.

Using TypoScript Settings `mainSectionOnly` you can also decide whether the entire content element including header and footer should be enclosed in the consent box or just the main content. The setting is documented in the :ref:`TypoScript constants reference <configuration-typoscript-constants>`.

..  note::
    If you have already overwritten this layout file through your site package, you will need to enter the required code into your version of the layout file yourself.

A special feature in this context is that using a string-based low-level replacement in the final HTML code, any occurrence of actively loaded resources within contextual content elements (via the `src` or `href` attribute) is automatically prevented. The external resources of Contextual Content will only be loaded after consent. You no longer need to worry about adjusting these attributes by adding `data-` as usual in Klaro. The middleware `ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput` is responsible for this.

..  figure:: /Images/Content-ContextualConsent.png
    :class: with-shadow
    :alt: TYPO3 content element field for selecting a contextual consent service

    Backend configuration of the content element

..  figure:: /Images/Frontend-ContextualConsent.png
    :class: with-shadow
    :alt: Frontend contextual consent placeholder for blocked external content

    Frontend display

..  youtube:: bK_XeJrlyW8


..  _klaroIsActive:

TypoScript condition to check if Klaro is used
==============================================

The variable `klaroIsActive` can be used in TypoScript conditions to check whether Klaro is active. Various conditions form the prerequisite for Klaro to be used:

- It is a frontend call
- A configuration record has been referenced in the site configuration
- At least one service has been referenced in the Klaro configuration
- The TypoScript constant `plugin.tx_klaroconsentmanager.settings.configuration.disabled` is `FALSE`.

Sometimes it is desired that sites or certain translations do not use Consent Management. This is the case, for example, if the website is outside the scope of the GDPR. In these cases, Klaro should not be loaded. In addition, script adjustments (e.g. the Klaro-typical change of the attribute `src` to `data-src`) must not be carried out. The variable `klaroIsActive` helps with the differentiation at TypoScript level.

..  code-block:: typoscript
    :linenos:
    :caption: **Example**

    [!klaroIsActive]
    page.headerData.100 = TEXT
    page.headerData.100 {
      value (
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id={$tagId}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());
        gtag('config', '{$tagId}', {'anonymize_ip':true});
    </script>
      )
    }
    [END]

    [klaroIsActive]
      page.headerData.100 = TEXT
      page.headerData.100 {
        value (
    <script
        async
        data-src="https://www.googletagmanager.com/gtag/js?id={$tagId}"
        type="text/plain"
        data-type="application/javascript"
        data-name="google-analytics"></script>
    <script
        type="text/plain"
        data-type="application/javascript"
        data-name="google-analytics">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments)};
        gtag('js', new Date());
        gtag('config', '{$tagId}', {'anonymize_ip':true});
    </script>
        )
      }
    [END]

..  _klaroIsActiveViewHelper:

Fluid ViewHelper to check if Klaro is used
==========================================

The Fluid ViewHelper ``{klaro:isActive()}`` returns a boolean that indicates whether Klaro is active for the
current frontend request. The prerequisites are identical to the TypoScript condition described in
:ref:`klaroIsActive feature <klaroIsActive>`. This makes it easy to switch between consent-aware and regular script
output in templates.

..  code-block:: html
    :linenos:
    :caption: **Example**

    <f:if condition="{klaro:isActive()}">
        <f:then>
            <f:asset.script identifier="instagram" type="text/plain" data-type="application/javascript" data-name="instagram" src="//www.instagram.com/embed.js"/>
        </f:then>
        <f:else>
            <f:asset.script identifier="instagram" src="//www.instagram.com/embed.js"/>
        </f:else>
    </f:if>

..  _features-service-filtering:

Service Filtering
=================

With this feature you can enable or disable individual services via TypoScript.
This is useful for conditions such as language, site identifier, or application context.
The setup reference documents the same options under
:ref:`configuration-typoscript-setup`.

- ``plugin.tx_klaroconsentmanager.settings.services.whitelist``
  Comma-separated list of service names. If defined, only these services are active.
  Any blacklist is ignored.

- ``plugin.tx_klaroconsentmanager.settings.services.blacklist``
  Comma-separated list of service names. If no whitelist is defined, all services are active except those listed.

Priority: Whitelist > Blacklist > Default (all active)

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

..  _features-standalone-configuration:

Standalone configuration
========================

If another application should use the Klaro configuration managed in TYPO3,
TYPO3 can return the active configuration as standalone JavaScript. The default
path is `/klaro-config.js` and can be changed in the
:ref:`global extension configuration <configuration-extension-configuration>`.
The path is resolved through TYPO3's site routing and returns a dedicated
`PAGE` type so the same TypoScript and Fluid label configuration is used as for
the frontend integration.

..  _features-csp-compliance:

CSP compliance
==============

Frontend CSS, JavaScript, and the standalone configuration asset are registered
through TYPO3's AssetCollector with nonce support. Trigger links are converted
to data attributes and click handlers instead of inline `onclick` attributes.
This keeps the extension compatible with TYPO3's Content Security Policy
handling in TYPO3 v13 and TYPO3 v14.

..  _features-compatibility:

TYPO3 v13 and v14 compatibility
===============================

The same extension release supports TYPO3 v13 and TYPO3 v14. :ref:`Site Sets
<configuration-site-set>` are the recommended integration path for both
versions. :ref:`Static TypoScript Includes <configuration-typoscript>` remain
available for projects that still use TypoScript records.

Backend module labels changed in TYPO3 v14, but the Klaro records, Site
Configuration fields, TypoScript settings, ViewHelper, TypoScript condition,
middleware, and frontend behaviour remain the same. See :ref:`compatibility`
for the exact TYPO3 v13/v14 backend paths and configuration differences.

..  _features-vanilla-javascript:

Vanilla JavaScript
==================

This integration is based exclusively on vanilla/plain JavaScript. No library such as jQuery is required. This way, only the code that is really needed is loaded, and you retain the freedom to decide whether and which library you want to use to run your site, completely independent of this Klaro integration.
