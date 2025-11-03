=== EDD for LearnDash ===
Author: LearnDash
Author URI: https://learndash.com
Plugin URI: https://learndash.com/add-on/easy-digital-downloads/ 
LD Requires at least: 3.0
Slug: learndash-edd
Tags: integration, membership, edd,
Requires at least: 5.0
Tested up to: 5.8
Requires PHP: 7.0
Stable tag: 1.4.0

Integrates LearnDash LMS with Easy Digital Downloads.

== Description ==

Integrate LearnDash LMS with Easy Digital Downloads.

Easy Digital Downloads is a popular shopping cart for WordPress with over 400,000 downloads. This integration makes it possible to sell LearnDash created courses using the Easy Digital Downloads shopping cart.

= Integration Features = 

* Easy course mapping
* Associate one, or multiple courses
* Auto-User Enrollment
* Works with any payment gateway

See the [Add-on](https://learndash.com/add-on/easy-digital-downloads/) page for more information.

== Installation ==

If the auto-update is not working, verify that you have a valid LearnDash LMS license via LEARNDASH LMS > SETTINGS > LMS LICENSE. 

Alternatively, you always have the option to update manually. Please note, a full backup of your site is always recommended prior to updating. 

1. Deactivate and delete your current version of the add-on.
1. Download the latest version of the add-on from our [support site](https://support.learndash.com/article-categories/free/).
1. Upload the zipped file via PLUGINS > ADD NEW, or to wp-content/plugins.
1. Activate the add-on plugin via the PLUGINS menu.

== Changelog ==

= 1.4.0 =

* Feature: Add support for EDD pro.
* Feature: Add variable pricing support for LearnDash course and group enrollment.
* Feature: Add retroactive tool based on EDD purchase and subscription.
* Feature: Add subscription completed handler and filter hook that allows access removal.
* Feature: Add user cancelled subscription check for expired subscription.
* Feature: Add subscription failing hook handler.
* Fix: Add backward compatibility with LD EDD <= 1.3.0 for variable products.
* Fix: Fix course access is given on any subscription update action.
* Fix: Add compatibility with EDD free downloads extension.
* Fix: Change course and group selector select fields to select2.
* Fix: Add EDD settings section on LD advanced settings page.
* Fix: Users don't get access to LD course/group after completing purchase using unregistered user.
* Fix: Variable product course selector doesn't work because of selector change in EDD 3.0.

= 1.3.0 =

* Added LearnDash group support 
* Updated user course access is attched to each product rather than the overall order allowing refunds of individual products whilst retaining access to others
* Updated updating product’s related courses will update existing customers course access
* Fixed PHP warnings 
* Fixed broken metabox styling

= 1.2.0 =

* Added a check on expired transaction status when removing course access
* Fixed users not being unenrolled on payment status change
* Fixed course unenrollment issue


View the full changelog [here](https://www.learndash.com/add-on/easy-digital-downloads/).