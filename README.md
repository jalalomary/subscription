Subscription
============


#### Contents
*   [Synopsis](#syn)
*   [Overview](#over)
*   [Installation](#install)
*   [Tests](#tests)
*   [Contributors](#contrib)
*   [License](#lic)


## <a name="syn"></a>Synopsis

A module that integrates Magento 2 with ShopGo's billing interface.

## <a name="over"></a>Overview

Subscription module integrates Magento 2 with ShopGo's billing interface, so that users could subscribe, upgrade or downgrade their stores.
The module also can tell users about their stores remaining days before the end of their subscriptions.

## <a name="install"></a>Installation

Below, you can find two ways to install the subscription module.

### 1. Install via Composer (Recommended)
First, make sure that Composer is installed: https://getcomposer.org/doc/00-intro.md

Make sure that Packagist repository is not disabled.

Run Composer require to install the module:

    php <your Composer install dir>/composer.phar require shopgo/subscription:*

### 2. Clone the subscription repository
Clone the <a href="https://github.com/shopgo-magento2/subscription" target="_blank">subscription</a> repository using either the HTTPS or SSH protocols.

### 2.1. Copy the code
Create a directory for the subscription module and copy the cloned repository contents to it:

    mkdir -p <your Magento install dir>/app/code/ShopGo/Subscription
    cp -R <subscription clone dir>/* <your Magento install dir>/app/code/ShopGo/Subscription

### Update the Magento database and schema
If you added the module to an existing Magento installation, run the following command:

    php <your Magento install dir>/bin/magento setup:upgrade

### Verify the module is installed and enabled
Enter the following command:

    php <your Magento install dir>/bin/magento module:status

The following confirms you installed the module correctly, and that it's enabled:

    example
        List of enabled modules:
        ...
        ShopGo_Subscription
        ...

## <a name="tests"></a>Tests

TODO

## <a name="contrib"></a>Contributors

Ammar (<ammar@shopgo.me>)

## <a name="lic"></a>License

[Open Source License](LICENSE.txt)
