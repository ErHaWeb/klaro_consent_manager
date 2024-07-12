..  include:: /Includes.rst.txt

========
Features
========

This extension impresses with a variety of features that improve normal use of Klaro and stand out from other Klaro integrations. See for yourself on this page.

.. contents::
   :local:

Backend GUI
===========

Klaro is normally configured via a `separate JavaScript file <https://github.com/klaro-org/klaro-js/blob/master/dist/config.js>`__, which is usually included in a web project alongside the actual Klaro JavaScript of the application. This form of integration is also frequently found in TYPO3 projects. Even some TYPO3 extensions that are based on Klaro only provide a placeholder for a `config.js` file.

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

This extension now takes a new approach by making **all parameters without exceptions** provided by the Klaro configuration file **editable via the TYPO3 backend** based on the TYPO3 :ref:`FormEngine <t3coreapi:FormEngine>` in a beautiful GUI with nice titles and comprehensible descriptive texts.

..  image:: /Images/Backend-GUIExample.png

In this way, even editors without JavaScript syntax knowledge can get the best out of Klaro. It also ensures that there are no syntax errors and that the output is reduced to the essentials in terms of performance.

In the end, the Klaro configuration is generated dynamically based on the backend settings and automatically integrated in the desired JavaScript format at the right place in the front end of the website.

Mutually exclusive parameters
=============================

In the context of the backend GUI, the extension automatically checks whether the relevant parameters are mutually exclusive. The backend interface is customised depending on the current setting. This means you no longer have to deal with the context and can configure Klaro more intuitively.

..  note::

    **Example**

    Activating the "Must consent" setting `[mustConsent]`, for example, means that "Notice As Modal" `[noticeAsModal]` is no longer displayed as an option in the backend interface. The reason for this is that Klaro inevitably uses a modal in such a case → A dependency that is not normally clear in `config.js`.

Neutral color scheme
====================

Apart from the default styling of Klaro, a color-neutral scheme is offered for selection. According to current case law, this is required, a point that has not yet been taken into account in Klaro's default CSS. In addition to this preset selection, the SCSS files supplied with this extension naturally also give you full freedom to customise the styling to suit your requirements.

XLIFF-based translations
========================

The code of the Klaro application normally contains labels for a variety of languages, which are used depending on the language of the output via JavaScript. Klaro also provides the option of overwriting labels of defined languages via configuration.

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

All labels that Klaro otherwise supplies on the JavaScript side were transferred to this format and uploaded to the Crowding translation server so that they can be maintained and expanded there by our active community.

..  note::
    From a technical perspective, these labels are ultimately delivered in the Klaro configuration under the global fallback index `zz`. The Klaro JavaScript knows no other source for labels than the one we provide through TYPO3. A more efficient way that combines the best of both worlds.

Fluid enriched labels
=====================

This extension offers the possibility of enriching or replacing any label with the help of fluid if necessary. Sometimes you want to enrich labels based on fluid functionality to add more dynamics. For example, in a multi-site installation it would be possible to output a site-specific consent modal title.

This also simplifies the use of HTML in labels. Not only can you use the full range of functions of Fluid to achieve dynamic HTML output for description texts, but the necessary character encoding of the texts in JavaScript is also taken care of for you.

After setting the template paths, the labels as a whole can be replaced with Fluid Content or Fluid Content service/purpose can be prepended/appended. The template path under "Templates/Labels/" is an UpperCamelCase representation of the label key. Dots "." are replaced by directory separator "/".

..  youtube:: NYb42MKC5hw

..  note::
    In multi-language instances, please use the label partial instead of the translate ViewHelper (contrary to the video). This ensures (outside the Extbase context) that the correct language key is used.

    ::

        {f:render(partial: 'Label', arguments: '{locallang:locallang}')}

Service presets
===============

Most common services that require consent, such as Google Analytics, Matomo, Facebook Pixel or YouTube, are already covered via preconfigured presets. In these cases, you only have to select the preset and you can use the description texts that have already been prepared.

..  image:: /Images/Service-PresetSelection.png

Cookie info table
=================

In addition to the description text of a service, all relevant cookie information is automatically displayed in a table. This is achieved by Klaro automatically enriching the `description` label of the service via the Fluid file `EXT:Resources/Private/Templates/Append/Service/Description.html`.

..  image:: /Images/Frontend-ServiceDescriptionExample.png

Trigger Links
=============

Certain link targets can be used to create links that open the Klaro Consent Management settings (`https://KLARO_CONSENT.com`) and reset the settings (`https://KLARO_RESET.com`). The link targets are replaced with CSP-compliant JavaScript functionality in the final HTML output. The links are also configurable via the extension settings. The middleware `ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput` is responsible for this.

..  youtube:: V4u4v0QS93s

Contextual Consent
==================

The Klaro Extension makes it possible to hide any content elements behind a consent requirement. Klaro’s contextual consent feature is used for this purpose. By default, the Fluid Styled Content Layout (`EXT:fluid_styled_content/Resources/Private/Layouts/Default.html`) is overwritten for this purpose.

Using TypoScript Settings `mainSectionOnly` you can also decide whether the entire content element including header and footer should be enclosed in the consent box or just the main content.

..  note::
    If you have already overwritten this layout file through your site package, you will need to enter the required code into your version of the layout file yourself.

A special feature in this context is that using a string-based low-level replacement in the final HTML code, any occurrence of actively loaded resources within contextual content elements (via the `src` or `href` attribute) is automatically prevented. The external resources of Contextual Content will only be loaded after consent. You no longer need to worry about adjusting these attributes by adding `data-` as usual in Klaro. The middleware `ErHaWeb\KlaroConsentManager\Middleware\ReplaceBeforeOutput` is responsible for this.

..  youtube:: bK_XeJrlyW8

Standalone configuration
========================

If you run another website on the **same** server (otherwise the loading would require consent) that should use the same Klaro configuration (e.g. as part of a WordPress instance, for the blog) you can also have the Klaro configuration returned as a standalone output by TYPO3. To do this, simply call `/klaro-config.js`. A MiddleWare (`ErHaWeb\KlaroConsentManager\Middleware\KlaroConfiguration`) that has been reduced to the essentials will take care of returning the necessary JavaScript code.

CSP compliance
==============

All resources that are integrated via the extension observe the content security policy if necessary. So there is no problem for this extension if the CSP feature Toggle is enabled (whether for the backend or frontend).

TYPO3 v13 compatibility
=======================

Any deprecations that appeared in v13 have been modernized. The entire code was optimized for PHP 8 and underwent various quality checks.

The TypoScript of this extension can already be integrated TYPO3 v13-compliant via SiteSet. Alternatively, it is still possible to integrate the TypoScript using the old method via static include of the `sys_template` record.
