..  include:: /Includes.rst.txt


..  _faq-missing-translation:

===================
Missing Translation
===================

Question
========

When I call up the page in the frontend, the title and description are shown as missing. What can I do?

..  figure:: /Images/FAQ-MissingTranslation.png
    :alt: Klaro frontend output showing missing translation placeholders for a custom service

Answer
======

The "missing translation" output occurs when Klaro expects a label that it
cannot find in the configured :ref:`Locallang files <howto-reference-xliff>`.

Only the services predefined as backend value-picker presets are provided in
the default Locallang file. :ref:`Custom services <howto-custom-service>` and
:ref:`custom purposes <howto-custom-purpose>` need labels in your own XLIFF
file.

In this case, according to the screenshot, you are using a self-defined service with the identifier `be_typo_user`. You now have to store your own labels for this custom service under the expected label keys `services.<IDENTIFIER>.title` and `services.<IDENTIFIER>.description`.

This will look like this in your locallang file:

..  code-block:: xml

    <trans-unit id="services.be_typo_user.title">
        <source>Title of the custom service "be_typo_user"</source>
    </trans-unit>
    <trans-unit id="services.be_typo_user.description">
        <source>Description of the custom service "be_typo_user"</source>
    </trans-unit>

The easiest way is to reference your own locallang file directly via the
:ref:`Locallang Path <configuration-content-locallangpath>` field of the Klaro
configuration in the backend:

..  figure:: /Images/FAQ-LocallangPath.png
    :alt: Locallang Path field used to reference a custom XLIFF file

This allows you to customise existing labels or add new labels.
