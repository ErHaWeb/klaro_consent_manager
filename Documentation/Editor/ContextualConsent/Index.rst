..  include:: /Includes.rst.txt

..  _for-editors-contextual-consent:

==================
Contextual Consent
==================

Contextual consent can be configured on content elements. The extension adds a
Klaro service selector to the `frames` palette of `tt_content`.
The feature behaviour and TypoScript setting are described in
:ref:`features-contextual-consent`.

..  figure:: /Images/Content-ContextualConsent.png
    :alt: TYPO3 content element with the Klaro service selector for contextual consent

    Contextual consent service selection on a content element

Backend path
============

..  list-table::
    :header-rows: 1

    *   - TYPO3 v13
        - TYPO3 v14
    *   - :guilabel:`Web` → :guilabel:`Page`
        - :guilabel:`Content` → :guilabel:`Layout`

Service
=======

..  confval:: tx_klaroconsentmanager_service

    :type: :ref:`Service <for-editors-service>`
    :Default: 0

    Select the Klaro service that protects this content element. The frontend
    integration uses this value to load the selected service as
    `contextualconsentService` for the content element layout.

    The selector is independent of the services referenced in the global Klaro
    configuration. If the same service should also be shown in the Klaro modal,
    reference it in the :ref:`Klaro configuration record
    <for-editors-configuration>` as well.
