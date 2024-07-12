..  include:: /Includes.rst.txt
..  index:: Templates; Override
..  _templates-override:

Overriding templates
====================

EXT:klaro_consent_manager is using Fluid as template engine.

This documentation won't bring you all information about Fluid but only the
most important things you need for using it. You can get
more information in the section :ref:`Fluid templates of the Sitepackage tutorial
<t3sitepackage:fluid-templates>`. A complete reference of Fluid ViewHelpers
provided by TYPO3 can be found in the  :ref:`ViewHelper Reference <t3viewhelper:start>`


..  index:: Templates; TypoScript

Change the templates using TypoScript constants
-----------------------------------------------

As any Extbase based extension, you can find the templates in the directory
:file:`Resources/Private/`.

If you want to change a template, copy the desired files to the directory
where you store the templates.

We suggest that you use a sitepackage extension. Learn how to
:ref:`Create a sitepackage extension <t3sitepackage:start>`.

..  code-block:: typoscript

    plugin.tx_klaroconsentmanager {
        view {
            templateRootPath = EXT:sitepackage/Resources/Private/Extensions/KlaroConsentManager/Templates/
            partialRootPath = EXT:sitepackage/Resources/Private/Extensions/KlaroConsentManager/Partials/
            layoutRootPath = EXT:sitepackage/Resources/Private/Extensions/KlaroConsentManager/Layouts/
        }
    }
