=== Smart Recent Posts Widget ===
Contributors: idenovasi, satrya
Donate link: https://paypal.me/satrya
Tags: recent posts, random posts, popular posts, thumbnails, widget, widgets, sidebar, excerpt, category, post tag, taxonomy, post type, post status, multiple widgets, recent posts widget
Requires at least: 5.0
Tested up to: 5.6
Stable tag: 1.0.1
Requires PHP: 5.6
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Provides advanced recent posts widget,you can display it with thumbnails, excerpt, date, author, comment count and more.

== Description ==

**Smart recent posts widget** provides flexible and highly customizable [recent posts widget](https://idenovasi.com/projects/smart-recent-posts-widget/). Allows you to display a list of the most recent posts with thumbnail, excerpt, date, author, comment count also you can display it from all or specific or multiple categories or tags, post types and much more! It is also support **Page Builder by Siteorigin** and **Elementor Page Builder**.

= Install, Activate, Done! =
Using the widget is super easy with clear inline information. It provides a lot of options to fit your needs, need more option? Please ask on **Support** forum.

= Features Include =

* **Style**: Choose 3 different posts list style
* Open link in new tab
* Show/hide post title
* Allow you to set title url
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

= Support this project =

* [Translate to your language](https://translate.wordpress.org/projects/wp-plugins/smart-recent-posts-widget/).
* Contribute on [Github](https://github.com/satrya/smart-recent-posts-widget).
* [Donate](https://paypal.me/satrya).

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

1. Modern style, tested with default theme Twenty Sixteen
2. Classic style
3. Default style
4. General settings
5. Posts settings
6. Taxonomy settings
7. Thumbnail settings
8. Excerpt settings
9. Display settings
10. Appearance settings

== Style Explanation ==

In version `0.0.3` we provide new **Style** option, there are 3 style you can choose:

- Default
- Classic
- Modern

**Default**
This is just a basic style, the posts list style follow your theme stylesheet.

**Classic**
Classic style is a basic style with some little improvement, it adds a nice border bottom to each list to seperate them and change the font color and size of the post meta and excerpt. This style works with whatever setting you choose, either small thumbnail or big thumbnail with centered position.

**Modern**
Modern style adds a nice opacity to the thumbnail and display the title on the corner of the thumbnail. This style **only** works perfectly with some condition, if you want to get the best result please follow below steps:

- Switch the **Thumbnail Size** to _medium_
- **Only** display the title, please uncheck another data such as **date**, **comment count**, **author**, etc.
- Remove everything inside the **Custom CSS** box.
- Switch the Style option to **Modern**
- Done!

Please open the **Screenshot** tab above to see the style design.

== Changelog ==

= 1.0.1 - Feb 15, 2021 =
* Maintenance update

= 1.0.0 =
* Support WordPress 5.6
