..  include:: /Includes.rst.txt


..  _for-editors-configuration-content:

=======
Content
=======

..  figure:: /Images/Configuration-Content.png
    :class: with-shadow
    :alt: Content tab of a Klaro configuration record

    Klaro Configuration - Content Tab

..  contents::
   :local:

Hide Buttons
============

Hide Decline All
----------------

..  confval:: hide_decline_all

    :type: boolean
    :Default: false

    Setting `hideDeclineAll` to `true` will hide the "decline" button in the consent modal and force the user to open the modal in order to change his/her consent or disable all third-party services. We strongly advise you to not use this feature, as it opposes the "privacy by default" and "privacy by design" principles of the GDPR (but might be acceptable in other legislations such as under the CCPA)

Hide Learn More
---------------

..  confval:: hide_learn_more

    :type: boolean
    :Default: false

    Setting `hideLearnMore` to `true` will hide the "learn more / customize" link in the consent notice. We strongly advise against using this under most circumstances, as it keeps the user from customizing his/her consent choices.

Hide Toggle All
---------------

..  confval:: hide_toggle_all

    :type: boolean
    :Default: false

    Setting `hideToggleAll` to `true` will hide the "toggle all" link in the consent notice.

Append Buttons
==============

Append Show Button
------------------

..  confval:: append_show_button

    :type: boolean
    :Default: false

    If this option is set, a button is created in the frontend (via JavaScript)
    directly in front of the closing body tag, which triggers the opening of
    the Klaro consent management. For links in existing page content, see
    :ref:`features-trigger-links`.

Append Reset Button
-------------------

..  confval:: append_reset_button

    :type: boolean
    :Default: false

    If this option is set, a button is generated in the frontend (via
    JavaScript) directly in front of the closing body tag, which triggers the
    resetting of all settings and the opening of the Klaro consent management.
    For links in existing page content, see :ref:`features-trigger-links`.

Translations
============

..  _configuration-content-fluidtemplaterootpath:

Fluid Template Root Path
------------------------

..  confval:: fluidtemplate_rootpath

    :type: string
    :Default: ''

    As an alternative to the :ref:`TypoScript configuration
    <configuration-typoscript-constants>` under
    `plugin.tx_klaroconsentmanager.view`, a Fluid root path can be defined here.
    This path has a higher priority than the paths configured in TypoScript.
    The directories `/Layouts/`, `/Partials/`, and `/Templates/` are expected
    under this path. Fluid templates created here are used to enrich Locallang
    labels with additional functionality or to replace them completely. Fluid
    templates that override labels are expected in `/Templates/Labels/`. The
    path convention is described in :ref:`howto-fluid-enriched-labels`.

..  _configuration-content-locallangpath:

Locallang Path
--------------

..  confval:: locallang_path

    :type: string
    :Default: ''

    Optional path to an additional XLIFF file that overwrites the default labels
    or adds new labels, for example for :ref:`custom purposes
    <howto-custom-purpose>` or :ref:`custom services <howto-custom-service>`.
    If Fluid templates exist for these labels, the templates take precedence
    over the XLIFF text. It is common to render the original XLIFF label from
    the Fluid template through the `Label` partial.
