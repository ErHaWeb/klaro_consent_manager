..  include:: /Includes.rst.txt


..  _howto-custom-purpose:

==============
Custom Purpose
==============

.. contents::
   :local:

TSconfig
========

Create your new purpose (for example with the key `mycoolpurpose`) via TSconfig in the following form:

..  code-block:: typoscript
    :linenos:

    TCEFORM {
      tx_klaroconsentmanager_service.purposes.addItems {
        mycoolpurpose = LLL:EXT:sitepackage/Resources/Private/Language/Klaro/locallang.xlf:purposes.mycoolpurpose.title
        mycoolpurpose.group = custom
      }
      # Also copy this configuration to the `purpose_order` field of the klaro configuration
      tx_klaroconsentmanager_configuration.purpose_order.addItems < .tx_klaroconsentmanager_service.purposes.addItems
    }

XLIFF
=====

In you `custom XLIFF <howto-reference-xliff>`__ file now you have to create new labels for your custom key:

..  code-block:: xml
    :linenos:

    <trans-unit id="purposes.mycoolpurpose.title" resname="purposes.mycoolpurpose.title">
        <source>My Cool Purpose</source>
    </trans-unit>
    <trans-unit id="purposes.mycoolpurpose.description" resname="purposes.mycoolpurpose.description">
        <source>This is the description of the new purpose.</source>
    </trans-unit>

Backend
=======

In the backend, the new purpose is now visible for services and as part of the sorting in the Klaro configuration under the defined title.

..  figure:: /Images/HowTo-CustomPurpose-Backend.png

Frontend
========

If the purpose of a service referenced in the Klaro configuration has been assigned, it also appears in the Consent Management modal in the frontend.

..  figure:: /Images/HowTo-CustomPurpose-Frontend.png
