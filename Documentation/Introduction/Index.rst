..  include:: /Includes.rst.txt

..  _introduction:

============
Introduction
============

..  _videos:

Videos
======

..  _videos-general:

General functionality
~~~~~~~~~~~~~~~~~~~~~

The following steps are shown here:

#. Creation of a service
#. Assigning the service in the Klaro! configuration
#. Assignment of the Klaro! Configuration in the Site Configuration

..  youtube:: Kimcr5qjFtk

..  note::
    Prerequisite for the output in the frontend is that **at least one service has been assigned** in the Klaro!
    configuration.

..  _videos-feature-trigger:

Feature: Klaro Trigger
~~~~~~~~~~~~~~~~~~~~~~

Demonstration of the Klaro Trigger feature. Certain link targets can be used to create links that open the Klaro
Consent Management settings ("https://KLARO_CONSENT.com") and reset the settings ("https://KLARO_RESET.com").
The link targets are replaced with CSP-compliant JavaScript functionality in the final HTML output.

..  youtube:: V4u4v0QS93s

..  _videos-feature-contextualconsent:

Feature: Contextual Consent
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Demonstration of the contextual consent feature. Using this setting it is possible to hide any content element behind a
contextual consent controlled by Klaro! Consent Management.

..  youtube:: bK_XeJrlyW8

..  note::
    Please make sure to add the static TypoScript to use this feature.

..  _videos-feature-fluidenrichedlabels:

Feature: Labels enriched with Fluid
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Demonstration of the possibility to enrich any Klaro! label with Fluid. After setting the template paths, the labels as
a whole can be replaced with Fluid Content or Fluid Content service/purpose can be prepended/appended. The template path
under "Templates/Labels/" is an UpperCamelCase representation of the label key. Dots "." are replaced by directory
separator "/".

..  youtube:: NYb42MKC5hw

..  note::
    In multi-language instances, please use the label partial instead of the translate ViewHelper (contrary to the
    video). This ensures (outside the Extbase context) that the correct language key is used.

    ::

        {f:render(partial: 'Label', arguments: '{locallang:locallang}')}

