Getting Started With SofdroAccountBundle
==================================

The Softdro account bundle is being for transaction account system. Single company
can record all the transaction with selling, purchasing. It's extendable 
with Inventory, Selling, Purchasing bundle. 


Prerequisites
-------------
FOSUserBundle
SonataAdminBundle


This version of the bundle requires Symfony 4+. If you are using an older
Symfony version, please check you own.


Translations
~~~~~~~~~~~~

If you wish to use default texts provided in this bundle, you have to make
sure you have translator enabled in your config.

.. code-block:: yaml

    # app/config/config.yml

    framework:
        translator: ~

For more information about translations, check `Symfony documentation`_.

Installation
------------

Installation is a quick (I promise!) 7 step process:

1. Download SoftdroAccountBundle using composer
2. Enable the Bundle
3. Create your Account class
5. Configure the SoftdroAccountBundle
6. Import DataFixture 
7. Update your database schema


Step 1: Download SoftdroAccountBundle using composer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~