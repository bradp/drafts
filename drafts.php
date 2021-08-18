<?php
/**
 * Plugin Name: Drafts
 * Description: Quickly and easily view the last draft posts.
 * Version:     1.0.0
 * Author:      Brad Parbs
 * Author URI:  https://bradparbs.com/
 * License:     GPLv2
 * Text Domain: drafts
 * Domain Path: /lang/
 *
 * @package drafts
 */

namespace Drafts;

use WP_Query;

// Add new dashboard widget with list of draft posts.
add_action(
	'wp_dashboard_setup',
	function () {
		wp_add_dashboard_widget(
			'drafts',
			sprintf(
				'<span><span class="dashicons dashicons-edit" style="padding-right: 10px"></span>%s</span>',
				esc_attr__( 'Recent Drafts', 'drafts' )
			),
			__NAMESPACE__ . '\\dashboard_widget'
		);
	}
);

/**
 * Add dashboard widget for draft posts.
 */
function dashboard_widget() {
	$posts = new WP_Query(
		[
			'post_type'      => get_post_types(),
			'orderby'        => 'modified',
			'post_status'    => 'draft',
			'order'          => 'DESC',
			'posts_per_page' => 25,
			'no_found_rows'  => true,
		]
	);

	$drafts = [];

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();

			$drafts[] = [
				'ID'      => get_the_ID(),
				'title'   => get_the_title(),
				'date'    => gmdate( 'F j, g:ia', get_the_time( 'U' ) ),
				'preview' => get_preview_post_link(),
			];
		}
	}

	printf(
		'<div id="draft-posts-widget-wrapper">
			<div id="draft-posts-widget" class="activity-block" style="padding-top: 0;">
				<ul>%s</ul>
			</div>
		</div>',
		display_draft_in_widget( $drafts ) // phpcs:ignore
	);
}
/**
 * Display draft posts in widget.
 *
 * @param array $posts Post data.
 *
 * @return string Output of post data.
 */
function display_draft_in_widget( $posts ) {
	$output = '';

	foreach ( $posts as $post ) {
		$output .= sprintf(
			'<li><em style="%4$s">%1$s</em> <a href="%2$s">%3$s</a></li>',
			isset( $post['date'] ) ? $post['date'] : '',
			isset( $post['preview'] ) ? $post['preview'] : '',
			isset( $post['title'] ) ? $post['title'] : '',
			'display: inline-block; margin-right: 5px; min-width: 125px; color: #646970;'
		);
	}

	return $output;
}
