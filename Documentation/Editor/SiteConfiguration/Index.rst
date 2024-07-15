..  include:: /Includes.rst.txt


..  _for-editors-site-configuration:

==================
Site Configuration
==================

..  figure:: /Images/SiteConfiguration.png

    Reference of the Klaro setting as well as the linking of the pages for the data protection declaration and the imprint

Tab: Klaro!
===========

Klaro! Configuration
--------------------

..  confval:: klaroConfiguration

    :type: :ref:`Configuration <for-editors-configuration>`
    :Default: 0

    Klaro configurations created in the root of this TYPO3 instance can be referenced here. If no configuration has been created yet, switch to the list module, select the root node of the page tree (with uid 0) and create a new configuration via the button "Create new record".

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
