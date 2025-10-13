..  include:: /Includes.rst.txt


..  _for-editors-cookie-general:

=======
General
=======

..  figure:: /Images/Service-Cookie.png

    Service Configuration - Cookie Tab

.. contents::
   :local:

Identification and presentation of the cookie
=======

Identifier
-----

..  confval:: identifier

    :type: string
    :Default: ''

    This is a unique identifier for this cookie. It must be written in lower case and must not contain any spaces, only the characters a-z, 0-9 and "-" or "_". The identifier can be used in Fluid, for example.

Title
-----

..  confval:: title

    :type: string
    :Default: ''

    If set, this title will be used in the frontend/backend for displaying the cookie. This title has no technical relevance (contrary to the `pattern` field).

Technically relevant settings
=======

This information is technically evaluated by Klaro! to manipulate the cookie.

Name / Pattern
-----

..  confval:: pattern

    :type: string
    :Default: ''

    Name or regular expression (regex)

    **Example:** `/^_pk_.*$/`

Path
-----

..  confval:: path

    :type: string
    :Default: ''

    Provide a path if you have services that set cookies for a path that is not "/"

Domain
-----

..  confval:: domain

    :type: string
    :Default: ''

    Provide a domain if you have services that set cookies for a domain that is not the current domain.

Expiration Time
=======

Expires after
-----

..  confval:: expires_after

    :type: string
    :Default: ''

    **Condition:** `expires_after_unit != 'end-of-session' AND expires_after_unit != 'persistent'`

    Number of years/months/days/hours/minutes/seconds that will elapse before this cookie expires.

Expires after unit
------------------

..  confval:: expires_after_unit

    :type: string
    :Default: ''

    Unit used for the "Expires after" specification. The special value `end-of-session` does not need a numeric value and hides the "Expires after" field accordingly.

    **Options:**

    *   Years `[years]`
    *   Months `[months]`
    *   Days `[days]`
    *   Hours `[hours]`
    *   Minutes `[minutes]`
    *   Seconds `[seconds]`
    *   End of session `[end-of-session]`
    *   Persistent `[persistent]`

    .. note::

        The option `persistent` should be used only if external sources
        (e.g. cookie scanners or provider documentation) list the cookie
        simply as *persistent* without specifying an exact expiry period.
        From a GDPR perspective, it is recommended to provide a concrete
        storage duration whenever possible.
