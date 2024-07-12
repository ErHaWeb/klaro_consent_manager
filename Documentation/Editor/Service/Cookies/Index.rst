..  include:: /Includes.rst.txt


..  _for-editors-service-cookies:

=======
Cookies
=======


..  figure:: /Images/Service-Cookie.png

    Service Configuration - Cookie Tab

.. contents::
   :local:

Cookie Settings
===============

Cookies
-----

..  confval:: cookies

    :type: :ref:`Cookie <for-editors-cookie>` (multiple)
    :Default: ''

    You can either only provide a cookie name or regular expression (regex) or a list consisting of a name or regex, a path and a cookie domain. Providing a path and domain is necessary if you have services that set cookies for a path that is not "/", or a domain that is not the current domain. If you do not set these values properly, the cookie can't be deleted by Klaro, as there is no way to access the path or domain of a cookie in JS. Notice that it is not possible to delete cookies that were set on a third-party domain, or cookies that have the HTTPOnly attribute: https://developer.mozilla.org/en-US/docs/Web/API/Document/cookie#new-cookie_domain
