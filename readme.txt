=== Jewtech Woo Pelecard Gateway ===
Original Contributors: idofri, ramiy. Modified 2021-07 by Nathan Rona
Tags: e-commerce, payments, gateway, checkout, pelecard, invoices, woo commerce, subscriptions
Requires at least: 5.5
Tested up to: 5.8
License: GPLv3 or higher

Extends WooCommerce with Pelecard payment gateway.
Modified by Nathan Rona, Jewtech 2021-11-09

Original plugin

/**
 * Plugin Name: Woo Pelecard Gateway
 * Plugin URI: https://wordpress.org/plugins/woo-pelecard-gateway/
 * Description: Extends WooCommerce with Pelecard payment gateway.
 * Version: 1.4.8
 * Author: Ido Friedlander
 * Author URI: https://profiles.wordpress.org/idofri/
 * Text Domain: woo-pelecard-gateway
 * Requires at least: 5.5
 * Requires PHP: 7.0
 *
 * WC requires at least: 3.0
 * WC tested up to: 5.4
 */


== Description ==

**This is the Pelecard payment gateway for WooCommerce.**

= About Pelecard =
[Pelecard](https://www.pelecard.com) provides clearing solutions for over 20 years, and gives a solution for secure and advanced enterprises both large and small, including websites.

= About the plugin =

The plugin allows you to use Pelecard payment gateway with the WooCommerce plugin.

= Features =
* Accept all major credit cards
* Responsive payment form
* Invoices & Receipts
* Subscriptions

== Installation ==

= Installation =
1. In your WordPress Dashboard go to "Plugins" -> "Add Plugin".
2. Search for "Jew-tech Woo Pelecard Gateway".
3. Install the plugin by pressing the "Install" button.
4. Activate the plugin by pressing the "Activate" button.
5. Open the settings page for WooCommerce and click the "Payments" tab.
6. Click on "Pelecard" in the payment-methods list.
7. Configure your Pelecard Gateway settings.

= Minimum Requirements =
* WordPress version 5.3 or greater.
* PHP version 7.0 or greater.
* MySQL version 5.6 or greater.

== Changelog  ==
=Modified=====
=1.0===
Added support for passing number of payment and minimum first payment with order as order-meta.
The meta-keys to use are billing_number_of_payments and billing_first_payment_min.

=Original================
= 1.4.8 =
* Add 3D-Secure params to J4 after J5 requests.
* Save total-payments for later use when doing J5 transactions.

= 1.4.7 =
* Bypass validation for J2 transactions.

= 1.4.6 =
* Disable payments transaction for subscription orders.

= 1.4.5 =
* Bypass validation for 3DS failed transactions.

= 1.4.4 =
* Fixed supported CC field.

= 1.4.3 =
* EZCount: add managed option for the `send_copy` parameter.

= 1.4.2 =
* Fixed J5 transactions logic.

= 1.4.1 =
* Fixed WPML/WCML integration.

= 1.4 =
* Added WooCommerce Subscriptions support.
* Added refund capabilities.
* Support J5 transactions.
* Added WPML/WCML support.
* Fixed checkout flow with IPN fallback.

= 1.3 =
* Added [iCount](https://www.icount.co.il/) support.
* Added [EZcount](https://www.ezcount.co.il/) support.

= 1.2.2 =
* Fixed syntax bug for PHP versions prior to 5.4.
* Fixed incorrect data sent to Tamal.

= 1.2.1 =
* Added filter hooks.
* Fixed HiddenPelecardLogo field logic.

= 1.2.0 =
* Restructured plugin.
* Added support for WC 3.x.
* Added Tokenization support.

= 1.1.12 =
* Added order discount for Tamal.

= 1.1.11 =
* Fixed Tamal default parameters.

= 1.1.10 =
* Fixed Tamal 'MaamRate' for Receipts.

= 1.1.9.4 =
* WordPress 4.7 compatible.
* Removed deprecated function(s).

= 1.1.9.3 =
* Added the 'wc_pelecard_gateway_request_args' filter hook.

= 1.1.9.2 =
* Added full transaction history
* Added gateway icon support (filter).
* Added advanced error logging.

= 1.1.9.1 =
* Fixed gateway response check.
* Fixed bug in constructor.

= 1.1.9 =
* Added the ability to customize min & max payments by cart's total.

= 1.1.8 =
* Added filter hooks.

= 1.1.7 =
* Fixed JS loading.

= 1.1.6 =
* Added Tamal document types.

= 1.1.5 =
* Added shipping to Tamal Invoices.

= 1.1.4 =
* Fixed major front-end bug.

= 1.1.3 =
* Added WordPress 4.5 & WooCommerce 2.5.5 compatibility

= 1.1.2 =
* Updated admin js.

= 1.1.1 =
* Update translation strings.
* Add translators comments.

= 1.1.0 =
* Added [Tamal API](https://www.accountbook.co.il/) for creating invoices.
* Improved tab-based admin menu.

= 1.0.5 =
* i18n: Remove po/mo files from the plugin.
* i18n: Use [translate.wordpress.org](https://translate.wordpress.org/) to translate the plugin.

= 1.0.4 =
* Updated plugin translation files.

= 1.0.3 =
* Added advanced gateway options.

= 1.0.2 =
* Improved data validations.

= 1.0.1 =
* Fixed XSS Vulnerability.

= 1.0.0 =
* First Release.

== Upgrade Notice ==

= 1.1.4 =
* Fixed major front-end bug.

= 1.1.3 =
* Added WordPress 4.5 & WooCommerce 2.5.5 compatibility

= 1.0.2 =
Improved data validations.

= 1.0.1 =
Fixed XSS Vulnerability.