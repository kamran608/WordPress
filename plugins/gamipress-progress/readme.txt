=== GamiPress - Progress ===
Contributors: gamipress, tsunoa, rubengc, eneribs
Tags: gamipress, gamification, point, achievement, rank, badge, award, reward, credit, engagement
Requires at least: 4.4
Tested up to: 6.4
Stable tag: 1.4.3
License: GNU AGPLv3
License URI: http://www.gnu.org/licenses/agpl-3.0.html

Attractively show to your users their progress of completion of any achievement.

== Description ==

Progress gives you the ability to easily show to your users their progress of completion of any achievement, step, points type, points award, rank and/or rank requirements.

With just a few controls, you will be able to setup different progression looks at frontend to let your user know graphically their current progress of completion.

In addition, Progress add-on includes a block, shortcode and widget to render a gamification element progress anywhere included a progress based on a custom goal (points amount, achievements unlocked and current rank).

Also, this add-on adds new features to extend and expand the functionality of GamiPress.

= Features =

* Ability to show the current progression of an achievement, step, points type, points award, rank and/or rank requirements.
* Easy controls with live preview to completely customize the progress look (text, progress bar, radial progress bar or images).
* Block to display anywhere the progress of an element or based on a custom goal (points amount, achievements unlocked and current rank).
* Shortcode to display anywhere the progress of an element or based on a custom goal (points amount, achievements unlocked and current rank).
* Widget to display on any sidebar the progress of an element or based on a custom goal (points amount, achievements unlocked and current rank).
* New fields and attributes on GamiPress blocks, shortcodes and widgets to manually show or hide the configured progress.

= Show the progress as you want =

* Plain text: Show the progress in text like "3/4" with the ability to customize the pattern to use.
* Progress Bar: Responsive progress bars with options to decorate them with an stripe effect and also animate this effect.
* Radial Progress Bar: Dynamic radial progress bars with options to decorate them with the colors you want.
* Images: Show the progress with images with the ability to customize their sizes.

== Installation ==

= From WordPress backend =

1. Navigate to Plugins -> Add new.
2. Click the button "Upload Plugin" next to "Add plugins" title.
3. Upload the downloaded zip file and activate it.

= Direct upload =

1. Upload the downloaded zip file into your `wp-content/plugins/` folder.
2. Unzip the uploaded zip file.
3. Navigate to Plugins menu on your WordPress admin area.
4. Activate this plugin.

== Frequently Asked Questions ==

== Changelog ==

= 1.4.3 =

* **Improvements**
* Improved features for compatibility with the latest version of GamiPress.

= 1.4.2 =

* **Improvements**
* Correctly display progress based on GamiPress - Restrict Unlock conditions.

= 1.4.1 =

* **Improvements**
* Improved progress calculation for ranks already earned.

= 1.4.0 =

* **Improvements**
* Improved progress calculation on time limited requirements (Requires GamiPress 2.0.2 or higher).

= 1.3.9 =

* **Improvements**
* Style improvements for progress bars rendered without any other element.

= 1.3.8 =

* **Improvements**
* Improved progress calculation on time limited requirements.

= 1.3.7 =

* **Improvements**
* Force no progress when user is not logged in.

= 1.3.6 =

* **Improvements**
* Improved progress calculation on time limited requirements.

= 1.3.5 =

* **Improvements**
* Make use of the GamiPress new functions to determine the progress calculation.
* Calculation speed improvement by reducing the number of queries to the database.
* Performance improvement by reducing the amount of data retrieved from the database.
* **Bug Fixes**
* Fixed step progress calculation on manually awarded steps.

= 1.3.4 =

* **Improvements**
* Updated deprecated jQuery functions.

= 1.3.3 =

* **Bug Fixes**
* Fixed current rank progress typo on configuration metas, which caused a message that current rank is not configure to show progress.

= 1.3.2 =

* **Bug Fixes**
* Make current rank progress display the current rank to unlock instead the current rank unlocked.

= 1.3.1 =

* **Bug Fixes**
* Fixed incorrect points earned amount calculation.

= 1.3.0 =

* **Improvements**
* Improved the progress calculation of ranks already earned.