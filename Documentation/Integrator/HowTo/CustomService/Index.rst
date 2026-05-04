..  include:: /Includes.rst.txt


..  _howto-custom-service:

==============
Custom Service
==============

..  contents::
   :local:

Backend
=======

Create your new service (for example with the name `mycoolservice`) as a new
:ref:`service record <for-editors-service>` in the backend:

..  figure:: /Images/HowTo-CustomService-Backend.png
    :class: with-shadow
    :alt: Custom Klaro service record in the TYPO3 backend

XLIFF
=====

In your :ref:`custom XLIFF <howto-reference-xliff>` file, create new labels
for your custom key:

..  code-block:: xml
    :linenos:

    <trans-unit id="services.mycoolservice.title">
        <source>My Cool Service</source>
    </trans-unit>
    <trans-unit id="services.mycoolservice.description">
        <source>This is the description of the new service.</source>
    </trans-unit>

Frontend
========

If the service was referenced in the :ref:`Klaro configuration
<for-editors-configuration>`, it also appears in the Consent Management modal
in the frontend.

..  figure:: /Images/HowTo-CustomService-Frontend.png
    :class: with-shadow
    :alt: Custom service displayed in the Klaro consent modal
