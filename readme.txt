=== Smart Recent Posts Widget ===
Contributors: 6hourcreative, satrya
Tags: recent posts, random posts, popular posts, thumbnails, widget, widgets, sidebar, excerpt, category, post tag, taxonomy, post type, post status, multiple widgets
Requires at least: 4.5
Tested up to: 4.6
Stable tag: 0.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Smart recent posts widget. Provides advanced recent posts widget. Display it with thumbnails, excerpt, date, author, comment count and more.

== Description ==

_The author is formerly Recent Posts Widget Extended author_

This plugin will enable a flexible and highly customizable recent posts widget. Allows you to display a list of the most recent posts with thumbnail, excerpt, date, author, comment count also you can display it from all or specific or multiple categories or tags, post types and much more!

= Install, Activate the widget, Done! =
Using the widget is super easy with clear inline information. It provides a lot of options to fit your needs, need more option? Please ask on **Support** forum.

= Features Include =

* Allow you to set title url.
* Selected or multiple post types
* Selected or multiple categories
* Selected or multiple tags
* Include or Exclude Categories
* Post status
* Custom html or text before and/or after recent posts
* Orderby date, comment count, random, and more
* Display thumbnails
* Display excerpt, with customizable length.
* Display post date
* Display modification date
* Display comment count
* Display post author
* Exclude current post
* Custom CSS
* Multiple widgets

= Visit Us =

* Twitter - [https://twitter.com/6hourcreative](https://twitter.com/6hourcreative)
* Facebook - [https://www.facebook.com/6hourcreative/](https://www.facebook.com/6hourcreative/)
* Website - [https://6hourcreative.com](https://6hourcreative.com)

== Installation ==

**Through Dashboard**

1. Log in to your WordPress admin panel and go to Plugins -> Add New
2. Type **smart recent posts widget** in the search box and click on search button.
3. Find **Smart Recent Posts Widget** plugin.
4. Then click on Install Now after that activate the plugin.
5. Go to the widgets page **Appearance -> Widgets**.
6. Find **Smart Recent Posts** widget.

**Installing Via FTP**

1. Download the plugin to your hardisk.
2. Unzip.
3. Upload the **smart-recent-posts-widget** folder into your plugins directory.
4. Log in to your WordPress admin panel and click the Plugins menu.
5. Then activate the plugin.
6. Go to the widgets page **Appearance -> Widgets**.
7. Find **Smart Recent Posts** widget.

== Frequently Asked Questions ==

= How to filter the post query? =
You can use `srpw_default_query_arguments` to filter it. Example:
`
add_filter( 'srpw_default_query_arguments', 'your_custom_function' );
function your_custom_function( $args ) {
	$args['posts_per_page'] = 10; // Changing the number of posts to show.
	return $args;
}
`

= How to filter the post excerpt? =
Post excerpt now comes with filter to easily dev to change/customize it. `apply_filters( 'srpw_excerpt', get_the_excerpt() )`

= Ordering not working! =
Did you installed any Post or Post Type Order? Please try to deactivate it and try again the ordering.

= No image options =
Your theme needs to support Post Thumbnail, please go to http://codex.wordpress.org/Post_Thumbnails to read more info and how to activate it in your theme.

= Available filters =
Default arguments
`
srpw_default_args
`

Post excerpt
`
srpw_excerpt
`

Post markup
`
srpw_markup
`

Post query arguments
`
srpw_default_query_arguments
`

== Screenshots ==

1. General settings
2. Posts settings
3. Taxonomy settings
4. Thumbnail settings
5. Excerpt settings
6. Meta settings
7. Custom CSS settings

== Changelog ==

= 0.0.2 - August 26, 2016 =
- Support WordPress 4.6

= 0.0.1 - August 1, 2016 =
- Initial release
