=== jqs-random ===
Contributors: jqs
Donate Link: http://iridani.com/projects/wordpress-plugins
Tags: random, content, quote, quotes, random words, jqs, ajax, jquery
Requires at least: 2.5
Tested up to: 2.5
Stable tag: 1.1

A simple random phrase plugin that can be used anywhere, via PHP or shortcodes.

== Description ==

I developed this little plugin as learning tool for delving into Wordpress v2.5's new shortcodes functionality, along with getting AJAX to work on the administration pages for a better interface. (If anything it is a great learning tool for anyone wanting to write a plugin.)

This plugin allows for any number of phrases to be used in a random manner about your website. Simply install and input your phrases in the option page and place your tags.

Future development:
*	Multiple random lists
*	Ability to choose a specific phrase by id

== Installation ==

Standard installation:

1.	Upload files to your */wp-content/plugins/* directory preserving the sub-folder structure.
1.	Activate the plugin via the 'Plugins' menu in WordPress.
1.	Add tags to your theme, posts and pages.

== Usage ==

The plugin can be called via three methods, two are for themes, and the third is for within a post/page's content.

Theme methods:

`<?php jqs_random(); ?>`
`<?php jqs_get_random(); ?>`

Both functions take one attribute $options which is an array that may contain any of the following boolean values:

`"stripwhitespace" => 0, "addslashes" => 0, "htmlspecialchars" => 0`

For inclusion within a post or page, use the new Shortcode;

`[jqs-random]`

and once again any of the three attributes can be added:

`[jqs-random stripwhitespace="0" addslashes="0" htmlspecialchars="0"]`

The three attributes are modifiers to the output so that you can always put the data where you want:

* stripwhitespace -- will replace all spaces with underscores.
* addslashes -- will return data after it is passed thru PHP's addslashes() function.
* htmlspecialchars -- will return data after is is pass thru PHP's htmlspecialchars() function.