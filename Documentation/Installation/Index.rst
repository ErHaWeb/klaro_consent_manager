..  include:: /Includes.rst.txt

..  _installation:

============
Installation
============

There are different ways an extension in TYPO3 can be installed. This also applies to this extension.

Composer
========

If your TYPO3 instance was installed with Composer (recommended), then this extension must also be installed via Composer. In this case, follow the chapter:

..  toctree::
   :maxdepth: 1
   :titlesonly:

   Composer/Index

Classic
=======

Otherwise, you can take the classic route and choose whether to download the
extension as a zip archive from the TYPO3 Extension Repository (TER) and import
it into the TYPO3 Extensions module or whether to search for and install it
directly there. In this case, follow the chapter:

..  toctree::
   :maxdepth: 1
   :titlesonly:

   Classic/Index

TYPO3 v13 and v14 backend paths
===============================

TYPO3 v14 renamed several backend modules. The installation steps are the same,
but the module paths differ. See :ref:`compatibility` for the full backend path
overview:

..  list-table::
    :header-rows: 1

    *   - Task
        - TYPO3 v13
        - TYPO3 v14
    *   - Apply database schema changes
        - :guilabel:`Admin Tools` → :guilabel:`Maintenance`
        - :guilabel:`System` → :guilabel:`Maintenance`
    *   - Install extensions in classic mode
        - :guilabel:`Admin Tools` → :guilabel:`Extensions`
        - :guilabel:`System` → :guilabel:`Extensions`
