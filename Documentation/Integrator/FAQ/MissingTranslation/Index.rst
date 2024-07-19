..  include:: /Includes.rst.txt


..  _faq:

===================
Missing Translation
===================

Question
========

When I call up the page in the frontend, the title and description are shown as missing. What can I do?

..  figure:: /Images/FAQ-MissingTranslation.png

Answer
======

The "missing translation" output always occurs when Klaro expects a label that it cannot find in the given Locallang files.

Only the services predefined in the backend select boxes are provided in advance as a label in the locallang file, unknown custom services are of course not initially provided.

In this case, according to the screenshot, you are using a self-defined service with the identifier `be_typo_user`. You now have to store your own labels for this custom service under the expected label keys `services.<IDENTIFIER>.title` and `services.<IDENTIFIER>.description`.

This will look like this in your locallang file:

..  code-block:: xml

    <trans-unit id="services.be_typo_user.title" resname="services.be_typo_user.title">
        <source>Title of the custom service "be_typo_user"</source>
    </trans-unit>
    <trans-unit id="services.be_typo_user.description" resname="services.be_typo_user.description">
        <source>Description of the custom service "be_typo_user"</source>
    </trans-unit>

The easiest way is to reference your own locallang file directly via the Klaro configuration in the backend:

..  figure:: /Images/FAQ-LocallangPath.png

This allows you to customise existing labels or add new labels.
