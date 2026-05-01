..  include:: /Includes.rst.txt

..  _for-editors:

===========
For Editors
===========

After installing this extension, you are able to create a Klaro :ref:`configuration <for-editors-configuration>` that is later referenced via the :ref:`Site Configuration <for-editors-site-configuration>` of the site for which Consent Management is to be set up.

:ref:`Services <for-editors-service>` are referenced via the Klaro configuration. Services can also be created independently of editing the Klaro configuration. In the context of editing services, information on the :ref:`cookies <for-editors-cookie>` used by the service in question can be specified inline.

The record forms are identical in TYPO3 v13 and TYPO3 v14. Only the backend
module paths differ. See :ref:`compatibility` for the complete backend path
overview:

..  list-table::
    :header-rows: 1

    *   - Task
        - TYPO3 v13
        - TYPO3 v14
    *   - Create or edit Klaro records
        - :guilabel:`Web` → :guilabel:`List`
        - :guilabel:`Content` → :guilabel:`Records`
    *   - Edit contextual consent on content elements
        - :guilabel:`Web` → :guilabel:`Page`
        - :guilabel:`Content` → :guilabel:`Layout`

General setup
=============

You can set up Consent Management in the following way:

..  rst-class:: bignums-tip

#.  Create :ref:`Services <for-editors-service>` including associated :ref:`cookies <for-editors-cookie>`

#.  Create a Klaro :ref:`configuration <for-editors-configuration>` and reference the services

#.  Reference the Klaro configuration in the :ref:`site configuration <for-editors-site-configuration>`

#.  Modify your third-party scripts as shown in the :ref:`Quick start guide <quickStart>`

Detailed information
====================

Here you will find detailed information on all fields that can be configured in
the site configuration, the Klaro configuration, the services, and their
cookies.

..  toctree::
   :maxdepth: 1
   :titlesonly:

   SiteConfiguration/Index
   Configuration/Index
   Service/Index
   Cookie/Index
   ContextualConsent/Index
