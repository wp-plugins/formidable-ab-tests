=== Formidable A/B Tests ===
Contributors: surfjam
Tags: a/b test, forms, formidable pro, ab testing, ab test
Requires at least: 3.0.1
Tested up to: 4.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily A/B test your Formidable Pro created forms.

== Description == 
A/B testing, or split testing, is the method of comparing two versions of a web page or call to action to see which performs better.You compare the two versions by showing one or the other variant to similar visitors at the same time. The one that gets a better conversion rate wins!

This plugin provides a shortcode to help you easily run your own A/B tests on forms created using Formidable Pro. With the provided shortcode you tell it two forms to alternate showing randomly and it tracks the conversion rate.
 

= How to use =
1. Install the plugin using the Installation directions
2. Add this shortcode to the page or widget area where you want the alternating forms to show: [inm_frm_ab_test forms="25, 26"]
3. Replace 25 & 26 with the form ids of your two forms.
4. Optionally add title="false", description="false" or minimize="1" to change these parameters from their default values.
5. View test results in Formidable > A/B Test.
6. When you feel you have enough data, stop the test by replacing the shortcode with a standard Formdiable shortcode to only show the winning form.
7. To start a new test, click the Reset Test Data button. This will clear all previously stored results and can't be undone.

It is a good idea to keep your test limited and specific. This means making small variations between the two forms for each test and keep them to a single element, such as the title or button text, etc. Making large changes to multiple elements at once may still give you a successful test, but it will be more difficult to understand which particular change caused the desired improvment.

== Installation ==

1. Upload `formidable-ab-test.zip` to the `/wp-content/plugins/` directory and unzip it there, or use the WordPress Dashboard to install it.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Create your forms as usual and use the provided shortcode to randomly display forms and record views.
4. See Formidable > A/B Test in the WP admin area for more detailed instructions.

== Frequently Asked Questions ==

= How do I A/B test forms? =

Look for the A/B Test admin page under the Formidable tab. Here you will find simple instructions and see your results.

= How many forms can I test? =

There are no set limits. It doesn't make sense, however, to test more than two variations at a time.

= How many tests can I run? =

Currently you can only run one test at a time. It's on the drawing board to add the ability to run multiple tests concurrently. 

There is an option to clear test data on the admin page so you can start a new test.

== Screenshots ==

1. See the results of your tests.

== Changelog ==

= 0.0 =
* First privately released beta May 26, 2015.

= 0.1 =
* Still in private beta.
* Minor changes to readme.
* Added screenshot.

= 0.2 =
* Still in beta, but added to the WordPress Repository for the first time.
* Updated the admin styling to match standard WP admin styles.

= 0.3 =
* Fixed a bug that prevented the database prefix from being added correctly to the table when created. This was preventing the plugin working properly.

== Upgrade Notice ==

= 0.3 =
Fixed a bug that prevented the plugin from recording views and submissions.