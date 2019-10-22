# Change Log for OXID eSales GDPR Opt-In Module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [v2.3.0] - 2019-10-22

### Changed
- Dropped support for PHP 7.0.

## [v2.2.1] - 2019-10-21

### Fixed
- Add checkboxes offset class for wave theme in registration, billing and shipping forms
- Fix checkboxes highlight on click (green text) in registration form to work separately for each checkbox.
- Module version number is now correct. 

## [v2.2.0] - 2019-07-12

### Added
- Column witdth is now flexible in contact form [PR-8](https://github.com/OXID-eSales/gdpr-optin-module/pull/8)

### Changed
- Dropped support for PHP 5.6.

### Fixed
- Check if checkbox should be visible on load [PR-7](https://github.com/OXID-eSales/gdpr-optin-module/pull/7)

## [v2.1.2] - 2018-10-11

### Fixed
- Fix unclosed "<strong>" elements [PR-6](https://github.com/OXID-eSales/gdpr-optin-module/pull/6)
- Remove unnecessary "checkbox" class usage [PR-6](https://github.com/OXID-eSales/gdpr-optin-module/pull/6)

## [v2.1.1] - 2018-07-06

### Changed
- Use dropdown instead of radio buttons for contact form module settings.

## [v2.1.0] - 2018-05-07

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

## [v2.0.0] - 2018-04-06

### Changed
- The GDPR Opt-In Module was fully ported as described in
  [modules porting guide](https://docs.oxid-esales.com/developer/en/6.0/update/eshop_from_53_to_6/modules.html).

[v2.3.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.2.1...v2.3.0
[v2.2.1]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.2.0...v2.2.1
[v2.2.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.1.2...v2.2.0
[v2.1.2]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.1.1...v2.1.2
[v2.1.1]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/OXID-eSales/gdpr-optin-module/compare/v1.0.0...v2.0.0
