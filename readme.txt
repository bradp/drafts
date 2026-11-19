=== Drafts ===
Contributors: bradparbs, surfboards
Tags: posts, widget, dashboard, content, calendar
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin to quickly and easily view all your recent draft posts.

== Description ==

Quickly and easily view all your recent draft posts.

== Available filters ==

`drafts_widget_icon` - The dashicon to use for the dashboard widget.
`drafts_widget_title` - The title to use for the dashboard widget.
`drafts_post_types_to_show` - An array of post types to show in the widget. Defaults to all public post types except attachments.
`drafts_widget_query_args` - An array of arguments to pass to the WP_Query.
`drafts_show_in_widget` - Will pass ID of each post, returning `false` will not add it to the dashboard widget.

== Changelog ==

= 1.1.1 =

* Newest drafts are shown first.
* Returning `false` from `drafts_show_in_widget` now hides the post, as documented.
* Dates use the site timezone, and titles and links are escaped.
* Menu items, templates, and other non-content post types are no longer included.
* Requires WordPress 6.0 and PHP 7.4, tested up to WordPress 7.1.

= 1.1.0 =

* Added more filters

= 1.0.0 =

* Initial release
