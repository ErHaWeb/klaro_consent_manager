..  include:: /Includes.rst.txt


..  _howto-preparations-for-customisation:

==============================
Preparations for customisation
==============================

Two references to your own sitepackage files can be stored in the Klaro configuration record in the backend. These are described below.

..  contents::
   :local:

..  _howto-reference-fluid:

Reference to your own Fluid files
=================================

A reference to the Fluid root directory can be configured via the
:ref:`Fluid Template Root Path <configuration-content-fluidtemplaterootpath>`
field of the Klaro configuration.

..  figure:: /Images/HowTo-FluidTemplateRootPath.png
    :class: with-shadow
    :alt: Fluid Template Root Path field in the Klaro configuration content tab

**Example**

..  code-block:: txt

    EXT:sitepackage/Resources/Private/

The directories `/Layouts/`, `/Partials/`, and `/Templates/` are expected
under the path specified here. Fluid-enriched labels are expected in the
`/Templates/Labels/` directory. See :ref:`howto-fluid-enriched-labels` for
details.

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

A reference to a Locallang file can be created via the
:ref:`Locallang Path <configuration-content-locallangpath>` field of the Klaro
configuration.

This overwrites the default labels of the extension. Labels can also be added to this file that are required for individually defined purposes or services, for example.
See :ref:`howto-custom-purpose`, :ref:`howto-custom-service`, and
:ref:`faq-missing-translation` for common use cases.

..  figure:: /Images/HowTo-LocallangPath.png
    :class: with-shadow
    :alt: Locallang Path field in the Klaro configuration content tab
