..  include:: /Includes.rst.txt


..  _for-editors-configuration-general:

=======
General
=======

..  figure:: /Images/Configuration-General.png
    :alt: General tab of a Klaro configuration record

    Klaro Configuration - General Tab

..  contents::
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

    :type: select (multiple)
    :Default: ''

    The order in which purposes appear in the notice and modal can be changed
    via the `purposeOrder` parameter. The field reuses the purpose options from
    service records and stores the selected purpose identifiers in the selected
    order.

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

    Select the third-party services that Klaro manages for this configuration.
    Services are independent records and can be created inline from this field
    or separately on the root node.
