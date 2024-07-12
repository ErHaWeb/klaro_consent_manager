..  include:: /Includes.rst.txt

..  _for-editors:

===========
For Editors
===========

After installing this extension, you are able to create a Klaro :ref:`configuration <for-editors-configuration>` that is later referenced via the :ref:`Site Configuration <for-editors-site-configuration>` of the site for which Consent Management is to be set up.

:ref:`Services <for-editors-service>` are referenced via the Klaro configuration. Services can also be created independently of editing the Klaro configuration. In the context of editing services, information on the :ref:`cookies <for-editors-cookie>` used by the service in question can be specified inline.

General setup
=============

You can set up Consent Management in the following way:

..  rst-class:: bignums-tip

1.  Create **services** including associated **cookies**

2.  Create a Klaro **configuration** and reference the services

3.  Reference the Klaro configuration in the site configuration

Detailed information
====================

Here you will find information about all fields that can be configured for the **configuration**, **services** and **cookies**.

..  toctree::
   :maxdepth: 1
   :titlesonly:

   SiteConfiguration/Index
   Configuration/Index
   Service/Index
   Cookie/Index
