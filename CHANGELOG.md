# Change Log for OXID eSales GDPR Opt-In Module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]

### Added

### Changed
 
### Deprecated

### Removed

### Fixed

### Security

## [2.1.0] - 2018-05-07

### Added
- Added opt-in for updating invoice address.
  * New module setting blOeGdprOptinInvoiceAddress.
  * Application/views/blocks/user_checkout_billing_feedback.tpl
  * Application/views/blocks/user_invoice_address_form.tpl
  
### Changed
- The following templates have been changed
  * Application/views/blocks/user_shipping_address.tpl renamed to Application/views/blocks/user_address.tpl
  * Application/views/blocks/user_checkout_shipping_change.tpl renamed to Application/views/blocks/user_checkout_change.tpl
  * Application/views/blocks/user.tpl
  * Application/views/blocks/user_shipping_address_form.tpl
  * Application/views/blocks/user_checkout_shipping_feedback.tpl
- Documentation was updated.  

## [2.0.0] - 2018-04-06

### Changed
- The GDPR Opt-In Module was fully ported as described in
  [modules porting guide](https://docs.oxid-esales.com/developer/en/6.0/update/eshop_from_53_to_6/modules.html).

[Unreleased]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.1.0...HEAD
[v2.1.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v1.0.0...v2.0.0
