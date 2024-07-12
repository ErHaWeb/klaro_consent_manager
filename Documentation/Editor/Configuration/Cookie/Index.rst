..  include:: /Includes.rst.txt


..  _for-editors-configuration-cookie:

======
Cookie
======

.. contents::
   :local:

Cookie Settings
===============

Cookie Domain
-------------

..  confval:: cookie_domain

    :type: string
    :Default: ''

    You can change the cookie domain for the consent manager itself. Use this if you want to get consent once for multiple matching domains. By default, Klaro will use the current domain. Only relevant if `storageMethod` is set to `cookie`.

Cookie Path
-----------

..  confval:: cookie_path

    :type: string
    :Default: ''

    Provide a path if you have services that set cookies for a path that is not "/"

Cookie expires after days
-------------------------

..  confval:: cookie_expires_after_days

    :type: string
    :Default: ''

    You can also set a custom expiration time for the Klaro cookie. By default, it will expire after 60 days. Only relevant if `storageMethod` is set to `cookie`.
