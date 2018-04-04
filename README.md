GDPR opt-in module
==================

Module adds opt-in functionality which is required for GDPR law.

## Installation

For installation instructions please see /documentation/UserManual_de.pdf in this package. 

This module requires the following OXID eShop compilation versions: 6.0.1 or above.

### Module installation via composer

In order to install the module via composer configure the repository in composer
* **composer config repositories.oxid-esales/gdpr-optin-module vcs https://github.com/OXID-eSales/gdpr-optin-module**
and then run one of the following commands in commandline in your shop base directory 
(where the shop's composer.json file resides).
* **composer require oxid-esales/gdpr-optin-module:^2.0.0** to install the released version compatible with OXID eShop 6.0.1 compilation
* **composer require oxid-esales/gdpr-optin-module:dev-master** to install the latest unreleased version from github

## Features

Module allows to control:
* the opt-in option for delivery addresses to be conform with GDPR
* the opt-in option for user registration to be conform with GDPR
* the opt-in option for review writing form to be conform with GDPR
* the information customer is shown regarding what happens to the contact form data 
  after the request has been processed. 

## License

Licensing of the software product depends on the shop edition used.
The software for OXID eShop Community Edition is published under the GNU General Public License v3.
You may distribute and/or modify this software according to the licensing terms published by the Free
Software Foundation. Legal licensing terms regarding the distribution of software being subject to GNU
GPL can be found under http://www.gnu.org/licenses/gpl.html.
The software for OXID eShop Professional Edition and Enterprise Edition is released under commercial
license. OXID eSales AG has the sole rights to the software. Decompiling the source code, unauthorized
copying as well as distribution to third parties is not permitted. Infringement will be reported to the
authorities and prosecuted without exception.
