=== WordPress Loop ===
Contributors: ptahdunbar
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=11341928
Tags: widget, pages, posts, attachments, post types
Requires at least: 2.9
Tested up to: 2.9
Stable tag: 0.2

A WordPress widget that gives you unprecendeted control over displaying your content.

== Description ==

The *WordPress Loop* widget was written to allow users that don't know their way around PHP to easily show their content in any way they'd like. 

The widget has over 35 options to choose from. Customize your WordPress loop by one or more post types, categories, tags, custom taxonomies, authors, dates, custom fields, and a whole lot more! 

In addition, it has support for post thumbnails, sticky posts, pagination, offsetting, customizable content length (by word count), and you can change the ordering from a variety of options. Oh, and you can also customize the .

*WordPress Loop* is truly an all-in-one solution for displaying your content on your site.

View the [FAQ](http://wordpress.org/extend/plugins/wordpress-loop/faq/) section for more info on how to use WordPress Loop.

== Frequently Asked Questions ==

= How does this widget work? =

The WordPress Loop utilizes the `WP_Query` class to generate the widget loops. `query_posts()` is *not* used as it only modifies the main `$wp_query` global which is not optimal when you have more than more loop on a page.

= What are the available shortcodes in this widget? =
The `before_content` and `after_content` sections may contain shortcodes.
In addition, the WordPress Loop widget comes bundled with:

* `[title]` - Displays the title of the post.
* `[author]` - Displays the author of the post.
* `[date]` - Displays the date the post was published.
* `[last_modified]` - Displays the date the post was last modified.
* `[comments]` - Displays the comment count of the post.
* `[time]` - Displays the time of day the post was posted.
* `[edit]` - Displays the edit link to edit the post.
* `[cats]` - Displays all categories (in a comma seperated link format) associated with the post.
* `[tags]` - Displays any tags (in a comma seperated link format) associated with the post.
* `[tax]` - Displays all taxonomies (in a comma seperated link format) associated with the post.

= What hooks are available in this widget? =

The WordPress Loop has several action hooks available throughout the loop process:

* `before_loop` - At the beginning of the loop
* `the_loop` - In the loop, after all the content
* `after_loop` - At the ending of the loop
* `loop_404` - When the loop can't find any post

== Installation ==

1. Upload 'wordpress-loop' to the '/wp-content/plugins/' directory.
1. Activate the plugin through the *Plugins* menu in WordPress.
1. Go to *Appearance > Widgets* and place the *WordPress Loop* widget where you want.

== Changelog ==

**0.2** _(01/25/2010)_

	* ADDED: ability to customize the main loop tag (div/ol/ul)
	* ADDED: new shortcode [last_modified]
	* UPDATED: readme.txt with more info
	
**0.1** _(01/24/2010)_

	* Initial release.
	
== Screenshots ==

1. View of the *WordPress Loop* widget settings.