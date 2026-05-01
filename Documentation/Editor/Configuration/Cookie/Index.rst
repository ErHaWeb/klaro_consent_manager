..  include:: /Includes.rst.txt


..  _for-editors-configuration-cookie:

======
Cookie
======


..  figure:: /Images/Configuration-Cookie.png
    :alt: Cookie tab of a Klaro configuration record

    Klaro Configuration - Cookie Tab

..  contents::
   :local:

Cookie Settings
===============

Cookie Domain
-------------

..  confval:: cookie_domain

    :type: string
    :Default: ''

    **Condition:** `storage_method == 'cookie'`

    You can change the cookie domain for the consent manager itself. Use this if you want to get consent once for multiple matching domains. By default, Klaro will use the current domain. Only relevant if :ref:`storageMethod <for-editors-configuration-advanced-storage-method>` is set to `cookie`.

Cookie Path
-----------

..  confval:: cookie_path

    :type: string
    :Default: ''

    **Condition:** `storage_method == 'cookie'`

    Provide a path if you have services that set cookies for a path that is not "/"

Cookie expires after days
-------------------------

..  confval:: cookie_expires_after_days

    :type: integer
    :Default: 60

    **Condition:** `storage_method == 'cookie'`

    You can set a custom expiration time for the Klaro consent cookie. Only
    relevant if :ref:`storageMethod <for-editors-configuration-advanced-storage-method>`
    is set to `cookie`.
