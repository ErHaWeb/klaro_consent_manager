..  include:: /Includes.rst.txt


..  _howto-custom-service:

==============
Custom Service
==============

.. contents::
   :local:

Backend
=======

Create your new service (for example with the name `mycoolservice`) as a new service record in the backend:

..  figure:: /Images/HowTo-CustomService-Backend.png

XLIFF
=====

In you `custom XLIFF <howto-reference-xliff>`__ file now you have to create new labels for your custom key:

..  code-block:: xml
    :linenos:

    <trans-unit id="services.mycoolservice.title" resname="purposes.mycoolpurpose.title">
        <source>My Cool Service</source>
    </trans-unit>
    <trans-unit id="services.mycoolservice.description" resname="purposes.mycoolpurpose.description">
        <source>This is the description of the new service.</source>
    </trans-unit>

Frontend
========

If the service was referenced in the Klaro configuration, it also appears in the Consent Management modal in the frontend.

..  figure:: /Images/HowTo-CustomService-Frontend.png
