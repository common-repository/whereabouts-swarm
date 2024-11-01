=== Whereabouts: Swarm ===
Contributors: florianziegler, haptiq
Tags: swarm, foursquare, location, timezone, travel, digitalnomad, nomad, widget, shortcode
Requires at least: 4.3
Tested up to: 5.5
Stable tag: 0.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display your current location, automatically updated by your latest Swarm check-in.

== Description ==

Use a widget or the shortcode [whereabouts-swarm] to display your current location anywhere on your website.

**Please note:** The location will only be displayed if a location has been set.

= Styling =

There is **no extra styling**. You can however do it yourself, in your theme. This is what the HTML looks like, depending on what you decide to display (in the settings):

`<div class="whereabouts-swarm-location">
	<img class="whereabouts-swarm-icon" src="..." alt="" />
	<span class="whereabouts-swarm-venue">Venue Name</span>
	<span class="whereabouts-swarm-city">City Name</span>
	<span class="whereabouts-swarm-country">Country Name</span>
	<span class="whereabouts-swarm-timezone">UTC Time Zone</span>
</div>`

= Requirements =
* PHP 5.3
* WordPress 4.3

= Support =
* [Open a new topic here](https://wordpress.org/support/plugin/whereabouts-swarm)

= Website =
* [Whereabouts](https://whereabouts.haptiq.dev)

= Author =
* [Website](https://florianziegler.de)
* [Twitter](https://twitter.com/damndirty)


== Installation ==

1. Upload the `whereabouts-swarm` folder to your `/wp-content/plugins` directory.

2. Activate the "Whereabouts: Swarm" plugin in the WordPress administration interface.

3. Go to "Settings" -> "Whereabouts: Swarm" and authenticate with your Swarm/Foursquare account.

= Widget =

Add the widget, like you do with every other widget. :)

= Shortcode =

Use the following shortcode to display your location:

`[whereabouts-swarm]`


== Screenshots ==

1. Settings page


== Changelog ==

= 0.5.0 =

* Change plugin home

= 0.4.0 =

* Now using the HTTP API for requests, duh!

= 0.3.0 =

* Use `curl` if `file_get_contents` is not available
* Add more meaningful error messages

= 0.2.1 =

* Fix PHP 7 issues

= 0.2.0 =

* Added a widget

= 0.1.0 =

* Hello World