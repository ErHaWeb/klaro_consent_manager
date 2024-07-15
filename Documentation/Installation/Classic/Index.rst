..  include:: /Includes.rst.txt

..  _installation-classic:

=======
Classic
=======

There are two different ways to install extensions via the Extension Manager in the TYPO3 backend.

-   Either the extension is found via the :ref:`search function in the backend <#searchandinstall>` and installed directly
-   or it is downloaded from the TYPO3 Extension Repository (TER) as a zip file and :ref:`uploaded in the backend <#uploadfromter>`.

Both ways are described here.

..  tip::

    With the classic installation (both ways), you do not have to worry about database updates as with a :ref:`Composer <#composer>` installation. These are carried out automatically for you in the background during the installation process.

Extension Manager
=================

Regardless of which of the two ways you have chosen, you must enter the **Extension Manager** in the backend. To do this, follow the steps below.

..  rst-class:: bignums

    #.  Open the TYPO3 backend.

    #.  Go to the **Extension Manager** Module under :guilabel:`Admin Tools` → :guilabel:`Extensions`.

..  _searchandinstall:

Search and install
------------------

Proceed as follows to install the extension based on a TER search in the backend:

..  rst-class:: bignums

    #.  Select :guilabel:`Get Extensions` in the module header.

    #.  Enter the extension key **klaro_consent_manager** in the search field.

    #.  In the result list click :guilabel:`Import & Install ⇓` under **Actions**

        ..  figure:: /Images/ExtensionManager-GetExtensions.png
            :class: with-shadow
            :alt: Get Extensions dialog

    #.  Finished!

        The following pop-up will be displayed in the backend.

        ..  figure:: /Images/ExtensionManager-SuccessfulInstallationPopUp.png
            :alt: Successful installation PopUp

..  _uploadfromter:

Upload Extension from TER
-------------------------

..  rst-class:: bignums

    #.  `Download the extension<https://extensions.typo3.org/extension/klaro_consent_manager>`__ as a ZIP file from the TER

        The file is called `klaro_consent_manager_X.X.X.zip`.

        X.X.X stands for the three-digit version number of the downloaded version.

    #.  In the **Extension Manager** in the backend select :guilabel:`Upload Extension` in the module header

    #.  Click on the file upload field under **Extension** and select the file `klaro_consent_manager_X.X.X.zip`.

    #.  If you have previously installed the extension in an older version, activate the option :guilabel:`Overwrite`.

    #.  Click :guilabel:`Upload!`

    #.  Finished!

        You will now see the following message

        ..  figure:: /Images/ExtensionManager-wasInstalledMessage.png
            :alt: Installation success message
