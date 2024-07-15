..  include:: /Includes.rst.txt


..  _for-editors-configuration-general:

=======
General
=======

..  figure:: /Images/Configuration-General.png

    Klaro Configuration - General Tab

.. contents::
   :local:

General
=======

Title
-----

..  confval:: title

    :type: string
    :Default: ''

    Title under which this configuration can be found.

Purpose Order
-------------

..  confval:: purpose_order

    :type: string/select
    :Default: ''

    The order in which purposes appear in the notice and modal can be changed via the `purposeOrder` parameter, which can be given a list with purpose names and will display those purposes in the order in which they appear in the list.

    **Options:**

    *   **Option Group: Custom**

        *   Any custom option defined in TSconfig ...

    *   **Option Group: Additional**

        *   Analytics `[analytics]`
        *   Security `[security]`
        *   Live Chat `[livechat]`
        *   Styling `[styling]`
        *   Videos `[videos]`
        *   Social Media `[social]`
        *   Miscellaneous `[misc]`

    *   **Option Group: Default**

        *   Functional `[functional]`
        *   Performance `[performance]`
        *   Marketing `[marketing]`
        *   Advertising `[advertising]`

Services
========

Services
--------

..  confval:: services

    :type: :ref:`Service <for-editors-service>` (multiple)
    :Default: ''

    Here you specify the third-party services that Klaro will manage for you.

