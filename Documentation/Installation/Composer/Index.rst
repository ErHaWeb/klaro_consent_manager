..  include:: /Includes.rst.txt

..  _installation-composer:

========
Composer
========

To install this extension with Composer, you must first define the package as a composer requirement and then perform a database update.

Install the extension
=====================

In your command line interface, change to the root directory of your project (where the `composer.json` of your project is located) and enter the following command:

..  code-block:: bash

    composer require erhaweb/klaro-consent-manager

or with ddev:

..  code-block:: bash

    ddev composer require erhaweb/klaro-consent-manager

Apply database changes
======================

Follow the steps below to make all necessary database changes:

..  rst-class:: bignums

    1.  Open the TYPO3 backend.

    2.  Go to the **Maintenance** Module under :guilabel:`Admin Tools` → :guilabel:`Maintenance`

    3.  In the card :guilabel:`Analyze Database Structure` click :guilabel:`Analyze database…`

        The following database changes are proposed:

        ..  figure:: /Images/Maintenance-AnalyzeDatabaseStructure.png
            :alt: Maintenance: Analyze Database Structure

    4.  Click the button :guilabel:`Apply selected changes`

..  tip::

    If you have installed the `TYPO3 Console Extension by Helmut Hummel <https://extensions.typo3.org/extension/typo3_console>`__, you can also create the missing tables and fields with the following command:

    ..  code-block:: bash

        typo3 database:updateschema "*.add,*.change"

    or with ddev:

    ..  code-block:: bash

        ddev typo3 database:updateschema "*.add,*.change"

..  note::

    **Why a database update?**

    This extension uses the database and the TYPO3 :ref:`FormEngine <t3coreapi:FormEngine>` to let you create the Klaro configuration in a nice GUI in the backend.

    For this purpose, the tables `tx_klaroconsentmanager_configuration`, `tx_klaroconsentmanager_service` and `tx_klaroconsentmanager_cookie` must be created. In addition, a field must be added to the `tt_content` table in order to be able to implement the contextual consent feature at content level.
