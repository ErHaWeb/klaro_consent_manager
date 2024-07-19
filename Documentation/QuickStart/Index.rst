.. include:: /Includes.rst.txt

.. _quickStart:

===========
Quick start
===========

..  rst-class:: bignums

#.  :ref:`Install <installation>` the Extension

#.  Include the Extension configuration

    The modern and recommended way to reference the configuration of this extension since TYPO3 v13 is via its **Site Set**. Alternatively, it is still possible to use the **Static TypoScript Include** in the template record.

    ..  accordion::
        :name: accordionAddConfiguration

        ..  accordion-item:: Site Set
            :name: siteSet
            :header-level: 2

            #.  Go to the Sites module under :guilabel:`Site Management` → :guilabel:`Sites`

            #.  Click :guilabel:`Edit site configuration` at the relevant Site

            #.  Select :guilabel:`Klaro! Consent Manager [erhaweb/klaro-consent-manager]` under :guilabel:`Sets for this Site`

            #.  Move it in the list above the entry for your sitepackage so that you can overwrite it later

            #.  Click :guilabel:`Save` and :guilabel:`Close`

            ..  tip::

                You will notice that the **constant editor can no longer be opened** if the configuration
                was referenced via **Site Set**. To overwrite the default setting values of
                `EXT:klaro_consent_manager/Configuration/Sets/KlaroConsentManager/settings.definitions.yaml`,
                use a file `config/sites/<site-identifier>/settings.yaml`.

                **Example:** Configuration of the extension of the Contextual Consent Box to the entire content element instead of just the main content

                ..  code-block:: yaml
                    :linenos:
                    :caption: `config/sites/<site-identifier>/settings.yaml`

                    plugin:
                      tx_klaroconsentmanager:
                        settings:
                          contextualconsent:
                            mainSectionOnly: 0


        ..  accordion-item:: Static TypoScript Include
            :name: typoScriptInclude
            :header-level: 2

            #.  Go to the Template module under :guilabel:`Site Management` → :guilabel:`TypoScript`

            #.  Select the root of your Site in the page tree

            #.  Select :guilabel:`Edit TypoScript Record` in the module header

            #.  If no Template record exists you need to create one by clicking :guilabel:`Create a root TypoScript record`

            #.  Click the button :guilabel:`Edit the whole TypoScript record`

            #.  Switch to the tab :guilabel:`Advanced Options`

            #.  Select :guilabel:`Klaro! Consent Manager (klaro_consent_manager)` under :guilabel:`Include TypoScript sets` → :guilabel:`Available Items`

            #.  Move it in the list above the entry for your sitepackage so that you can overwrite it later

            #.  Click :guilabel:`Save` and :guilabel:`Close`

#.  Go to the Page module under :guilabel:`Web` → :guilabel:`List`

#.  In the page tree select the **global root node** (recommended) or any other storage page

#.  **For each service** proceed with the following steps ...

#.  Create a :ref:`Service <for-editors-service>`

    #.  Click :guilabel:`Create new record` in the module header of :guilabel:`Web` → :guilabel:`List`

    #.  Select :guilabel:`Klaro! Consent Manager` → :guilabel:`Klaro! Service` to create new service

    #.  Select a :guilabel:`Title` under which the service will later be identified **in the backend**. If it is a service that is represented in the list of presets, select it from the list. Otherwise, you must select your own title

    #.  Select a unique :guilabel:`Name` under which the service will later be identified **by Klaro**. If it is a service that is represented in the list of presets, select it from the list. Otherwise, you must select your own name

        ..  tip::

            The advantage of using a name from this list is that the associated supplied title and description texts for the frontend are used automatically. Of course, these can still be customised later

    #.  Under :guilabel:`Purposes` choose a purpose that best suits this service

    #.  Click :guilabel:`Save`

#.  **For each cookie** associated with the service proceed with the following steps ...

#.  Create a :ref:`Cookie <for-editors-cookie>` for the Service

    #.  In the editing mask of the service click Tab :guilabel:`Cookies`

    #.  Under :guilabel:`Cookie Settings` → :guilabel:`Cookies` click :guilabel:`+ Create new`

    #.  Scroll to palette :guilabel:`Identification and presentation of the cookie`

    #.  Define an :guilabel:`Identifier` for later usage in Fluid

    #.  Define a :guilabel:`Title` for frontend display

    #.  Scroll to palette :guilabel:`Technically relevant settings`

    #.  Define a :guilabel:`Name / Pattern` to technically enable Klaro to manipulate this cookie

    #.  Provide a :guilabel:`Path` if the cookies path is not "/"

    #.  Provide a :guilabel:`Domain` if the cookies domain is not the current one

    #.  Scroll to palette :guilabel:`Expiration Time`

    #.  Enter the number of years/months/days/hours/minutes/seconds that will elapse before this cookie expires under :guilabel:`Expiration Time` if the cookies domain is not the current one

    #.  Enter the expiration unit to be used under :guilabel:`Expires after unit`

    #.  Click :guilabel:`Save`

#.  When all cookies have been created in the service click :guilabel:`Close`

#.  When all services have been created continue with the next steps

    ..  note::

        Prerequisite for the output in the frontend is that **at least one service has been assigned**.

#.  Create the main Klaro :ref:`Configuration <for-editors-configuration>`

    #.  Click :guilabel:`Create new record` in the module header of :guilabel:`Web` → :guilabel:`List`

    #.  Select :guilabel:`Klaro! Consent Manager` → :guilabel:`Klaro! Configuration` to create new base configuration

    #.  Scroll to palette :guilabel:`General`

    #.  Set the :guilabel:`Title` under which this configuration can be found

    #.  If you attach importance to the order of purposes in the frontend output, define a series of purposes in the desired order under :guilabel:`Purpose Order`

    #.  Scroll to palette :guilabel:`Services`

    #.  Under :guilabel:`Services` select all Services that should be associated with this Configuration

    #.  Note the order of the services. Within a purpose, this sequence is taken into account between the services in the frontend output.

    #.  Click :guilabel:`Save` and :guilabel:`Close`

#.  Reference the Klaro Configuration via the :ref:`Site Configuration <for-editors-site-configuration>`

    #.  Go to the Sites module under :guilabel:`Site Management` → :guilabel:`Sites`

    #.  Click :guilabel:`Edit site configuration` at the relevant Site

    #.  Click on tab :guilabel:`klaro!`

    #.  Select your configuration under :guilabel:`Klaro! Configuration`

    #.  Define a :guilabel:`Privacy Policy URL` (possibly as an internal link)

    #.  Define a :guilabel:`Imprint URL` (possibly as an internal link)

#.  Modify your third-party scripts

    To make sure that no third-party scripts are loaded without consent, you need to modify your HTML code a tiny bit.

    #.  Replace the value of the `type` attribute with `text/plain` (this keeps the browser from executing the script)

    #.  Add a data attribute with the original type, e.g. `data-type="application/javascript"`

    #.  Add a `data-name` attribute that matches the name of the given :ref:`service <for-editors-service>` in your config, e.g. `data-name="google-analytics"`

    #.  In the case of an external script, also replace the `src` attribute with `data-src`

    The required customisation is shown below using the example of a typical gtag.js code for the integration of Google Analytics 4:

    ..  code-block:: html
        :linenos:
        :caption: **Before**

        <script
            async
            src="https://www.googletagmanager.com/gtag/js?id=TAG_ID"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments)};
            gtag('js', new Date());
            gtag('config', 'TAG_ID', {'anonymize_ip':true});
        </script>

    ..  code-block:: html
        :linenos:
        :caption: **After**

        <script
            async
            data-src="https://www.googletagmanager.com/gtag/js?id=TAG_ID"
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
            gtag('config', 'TAG_ID', {'anonymize_ip':true});
        </script>

    ..  note::

        This also works for other tags such as images or tracking pixels. Just remember to always add a `data-name` attribute that matches the name of the app in your config so that Klaro knows which element belongs to which :ref:`service <for-editors-service>`.

    ..  tip::

        If the script is integrated via TypoScript and is to be used in the context of both a page requiring consent and a page not requiring consent, take a look at the :ref:`klaroIsActive feature <klaroIsActive>`.

#.  Finished!

    **Congratulations, the Klaro configuration has been successfully completed**

    The next frontend call of the configured Site will show you a brand new Klaro Consent Management Box. You have probably noticed that there are a few more tabs (especially in the context of the main configuration). Click through here to find out more. Each field is described in detail in the backend.
