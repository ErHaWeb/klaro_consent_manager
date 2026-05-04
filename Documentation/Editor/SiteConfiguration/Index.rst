..  include:: /Includes.rst.txt


..  _for-editors-site-configuration:

==================
Site Configuration
==================

..  figure:: /Images/SiteConfiguration.png
    :class: with-shadow
    :alt: Klaro tab in the TYPO3 site configuration with configuration, privacy policy, and imprint fields

    Reference of the Klaro setting as well as the linking of the pages for the data protection declaration and the imprint

Tab: Klaro!
===========

Klaro! Configuration
--------------------

..  confval:: klaroConfiguration

    :type: :ref:`Configuration <for-editors-configuration>`
    :Default: 0

    Klaro configurations created in the root of this TYPO3 instance can be
    referenced here. If no :ref:`Klaro configuration record
    <for-editors-configuration>` has been created yet, create it on the root
    node of the page tree, UID `0`.

    ..  list-table::
        :header-rows: 1

        *   - TYPO3 v13
            - TYPO3 v14
        *   - Use :guilabel:`Web` → :guilabel:`List`.
            - Use :guilabel:`Content` → :guilabel:`Records`.

Privacy Policy URL
------------------

..  confval:: klaroPrivacyPolicyUrl

    :type: :ref:`Link <t3tca:columns-link>`
    :Default: ''

    The link to the privacy policy page is used in the introductory text of the Klaro! Consent Box.

Imprint URL
-----------

..  confval:: klaroImprintUrl

    :type: :ref:`Link <t3tca:columns-link>`
    :Default: ''

    The link to the imprint page is used in the footer of the Klaro! Consent Box.
