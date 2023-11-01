# Mage2 Module Bdollarapps ReportSystem

    ``bdollarapps/module-reportsystem``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
To manage Survey form reports

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Bdollarapps`
 - Enable the module by running `php bin/magento module:enable Bdollarapps_ReportSystem`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require bdollarapps/module-reportsystem`
 - enable the module by running `php bin/magento module:enable Bdollarapps_ReportSystem`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration




## Specifications

 - Block
	- Report\UsageReport > report/usagereport.phtml

 - Block
	- Report\AvailableAssets > report/availableassets.phtml

 - Block
	- Report\Life > report/life.phtml

 - Block
	- Report\Recruit > report/recruit.phtml

 - Block
	- Report\AssetSummary > report/assetsummary.phtml

 - Block
	- Report\CheckupResults > report/checkupresults.phtml

 - Block
	- Report\Index > report/index.phtml

 - Controller
	- frontend > report/report/usagereport

 - Controller
	- frontend > report/report/availablesssets

 - Controller
	- frontend > report/report/life

 - Controller
	- frontend > report/report/recruit

 - Controller
	- frontend > report/report/assetsummary

 - Controller
	- frontend > report/report/checkupresults

 - Controller
	- frontend > report/report/index


## Attributes



