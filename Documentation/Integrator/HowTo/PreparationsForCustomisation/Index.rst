..  include:: /Includes.rst.txt


..  _howto-preparations-for-customisation:

==============================
Preparations for customisation
==============================

Two references to your own sitepackage files can be stored in the Klaro configuration record in the backend. These are described below.

.. contents::
   :local:

..  _howto-reference-fluid:

Reference to your own Fluid files
=================================

A reference to the Fluid Root Directory can be configured via the `Fluid Template Root Path <configuration-content-fluidtemplaterootpath>`__ field of the Klaro configuration.

..  figure:: /Images/HowTo-FluidTemplateRootPath.png

**Example**

..  code-block:: txt

    EXT:sitepackage/Resources/Private/

The directories `/Layouts/`, `/Partials/` and `/Templates/` are automatically expected under the path specified here. Fluid enriched labels are expected in the `/Templates/Labels/` directory. For further information about fluid enriched labels `see here <howto-fluid-enriched-labels>`__.

..  directory-tree::
    :level: 5
    :show-file-icons: true

    *   EXT:sitepackage

        *   Resources

            *   Private

                *   Layouts

                *   Partials

                *   Templates

                    *   Labels

..  _howto-reference-xliff:

Reference to your own XLIFF file
================================

A reference to a Locallang file can be created via the `Locallang Path <configuration-content-locallangpath>`__ field of the Klaro configuration.

This overwrites the default labels of the extension. Labels can also be added to this file that are required for individually defined purposes or services, for example.

..  figure:: /Images/HowTo-LocallangPath.png
