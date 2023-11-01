# Mage2 Module Bdollarapps Subscription

    ``bdollarapps/module-subscription``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Amasty Customize Subscription

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Bdollarapps`
 - Enable the module by running `php bin/magento module:enable Bdollarapps_Subscription`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require bdollarapps/module-subscription`
 - enable the module by running `php bin/magento module:enable Bdollarapps_Subscription`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Controller
	- frontend > rvp_management/index/index


## Attributes

 - Customer - Billsby Subscription Data (billsby_subscription_data)

