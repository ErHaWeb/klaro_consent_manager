..  include:: /Includes.rst.txt


..  _howto-fluid-enriched-labels:

=====================
Fluid enriched labels
=====================

.. contents::
   :local:

Introduction
============

Once you have created a `reference to your own Fluid base path <howto-reference-fluid>`, you can extend or overwrite labels by adding new Fluid HTML files under `/Templates/Labels/` according to the XLIFF label key of the label to be changed.

When creating the files, please note that each `.` symbolises a directory and each segment is created in UpperCamelCase format. The last segment of the label key is the `*.html` file in which the modification can be made.

File Structure
==============

For example the following label with key `purposes.analytics.description`

..  code-block:: xml
    :linenos:
    :caption: EXT:klaro_consent_manager/Resources/Private/Language/locallang.xlf

    <trans-unit id="purposes.analytics.description" resname="purposes.analytics.description">
    	<source>These services gather anonymous data for statistical analysis and performance optimization. Enabling analytics services assists website owners in making informed decisions to enhance online services.</source>
    </trans-unit>

can be create under the following file structure if the configured base path is `EXT:sitepackage/Resources/Private/`

..  directory-tree::
    :level: 4

    *   EXT:sitepackage/Resources/Private/Templates/Labels/

        *   Purposes

            *   Analytics

                *   Description.html


Template File
=============

If you use the DebugViewHelper to display the available variables in the newly created template file, you will notice that all information can be used in the context of the current label.

..  code-block:: html
    :linenos:
    :caption: EXT:sitepackage/Resources/Private/Templates/Labels/Purposes/Analytics/Description.html

    <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers" data-namespace-typo3-fluid="true" lang="en">
        <f:debug>{_all}</f:debug>
    </html>

..  figure:: /Images/HowTo-Debug.png

Now you are free to decide what you want to use in place of the label. Please note that the `extensionName="KlaroConsentManager"` attribute is required in the context of using the Translate ViewHelper, as we are not working in the Extbase context here.

..  code-block:: html
    :linenos:

    <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers" data-namespace-typo3-fluid="true" lang="en">
        <f:comment><!-- Output of the label --></f:comment>
        <f:translate key="{locallang.key}" extensionName="KlaroConsentManager"/>
        <f:comment><!-- Or any dynamic content --></f:comment>
        <f:format.date format="Y">now</f:format.date>
    </html>

..  note::
    In multi-language instances, please use the label partial instead of the translate ViewHelper (contrary to the video). This ensures (outside the Extbase context) that the correct language key is used.

    ::

        {f:render(partial: 'Label', arguments: '{locallang:locallang}')}

HTML Usage
==========

If you want to use HTML tags in the label in addition to the pure text output, you must activate the `HTML Texts <configuration-layout-htmltexts>`__ option.

..  warning::

    Please note that Klaro itself does not support HTML code in every label. In such cases, the HTML characters may be encoded.

Full Structure
==============

Based on the currently used labels, the expected structure in the `Labels` directory is as follows. Depending on your requirements, you can create one or more files for modifying the label under this structure.

..  directory-tree::
    :level: 2
    :show-file-icons: true

    *   EXT:sitepackage/Resources/Private/Templates/Labels/

        *   ConsentManager

            *   Reset.html
            *   Show.html

        *   ConsentModal

            *   PrivacyPolicy

                *   Name.html
                *   Text.html

            *   Description.html
            *   Title.html

        *   ConsentNotice

            *   Imprint

                *   Name.html

            *   PrivacyPolicy

                *   Name.html

            *   ChangeDescription.html
            *   Description.html
            *   LearnMore.html
            *   Testing.html
            *   Title.html

        *   ContextualConsent

            *   AcceptAlways.html
            *   AcceptOnce.html
            *   Description.html

        *   Cookies

            *   Headline

                *   Name.html
                *   Path.html
                *   Domain.html
                *   ExpiresAfter.html

            *   Description.html
            *   Title.html

        *   PrivacyPolicy

            *   Name.html
            *   Text.html

        *   PurposeItem

            *   Service.html
            *   Services.html

        *   Purposes

            *   Functional

                *   Description.html
                *   Title.html

            *   Marketing

                *   Description.html
                *   Title.html

            *   Performance

                *   Description.html
                *   Title.html

            *   …

                *   Description.html
                *   Title.html

        *   Service

            *   DisableAll

                *   Description.html
                *   Title.html

            *   OptOut

                *   Description.html
                *   Title.html

            *   Required

                *   Description.html
                *   Title.html

            *   Purposes.html
            *   Purpose.html

        *   Services

            *   Adsense

                *   Description.html
                *   Title.html

            *   Camera

                *   Description.html
                *   Title.html

            *   Clarity

                *   Description.html
                *   Title.html

            *   …

                *   Description.html
                *   Title.html

        *   Warning

            *   NoServices.html
            *   NoServicesPersonalized.html

        *   AcceptAll.html
        *   AcceptSelected.html
        *   Close.html
        *   Day.html
        *   Days.html
        *   Decline.html
        *   End-of-session.html
        *   Hour.html
        *   Hours.html
        *   Minute.html
        *   Minutes.html
        *   Month.html
        *   Months.html
        *   Ok.html
        *   PoweredBy.html
        *   Save.html
        *   Second.html
        *   Seconds.html
        *   Year.html
        *   Years.html

Append/Prepend Content
======================

In addition to the manipulation of labels, it is also possible to display **additional** content **before** and/or **after** **all** **titles** and/or **descriptions** of **purposes** and/or **services**.

The following files can be used for this purpose as required:

..  directory-tree::
    :level: 4
    :show-file-icons: true

    *   EXT:sitepackage/Resources/Private/Templates/

        *   Append

            *   Purpose

                *   Description.html
                *   Title.html

            *   Service

                *   Description.html
                *   Title.html

        *   Prepend

            *   Purpose

                *   Description.html
                *   Title.html

            *   Service

                *   Description.html
                *   Title.html
