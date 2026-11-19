<?php
/**
 * Plugin Name:       Drafts
 * Description:       Quickly and easily view the last draft posts.
 * Version:           1.1.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Brad Parbs
 * Author URI:        https://bradparbs.com/
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       drafts
 *
 * @package drafts
 */

namespace Drafts;

use WP_Query;

defined( 'ABSPATH' ) || exit;

add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\add_dashboard_widget' );

/**
 * Add new dashboard widget with list of draft posts.
 */
function add_dashboard_widget() {
	$name = sprintf(
		'<span><span class="dashicons %s" style="padding-right: 10px"></span>%s</span>',
		esc_attr( apply_filters( 'drafts_widget_icon', 'dashicons-edit' ) ),
		apply_filters( 'drafts_widget_title', esc_html__( 'Recent Drafts', 'drafts' ) )
	);

	wp_add_dashboard_widget( 'drafts', $name, __NAMESPACE__ . '\\dashboard_widget' );
}

/**
 * Get the post types to show in the widget.
 *
 * @return string|string[] Post type(s).
 */
function get_widget_post_types() {
	return apply_filters(
		'drafts_post_types_to_show',
		array_values( array_diff( get_post_types( [ 'public' => true ] ), [ 'attachment' ] ) )
	);
}

/**
 * Add dashboard widget for draft posts.
 */
function dashboard_widget() {
	$query_args = apply_filters(
		'drafts_widget_query_args',
		[
			'post_type'      => get_widget_post_types(),
			'post_status'    => 'draft',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'posts_per_page' => 25,
			'no_found_rows'  => true,
		]
	);

	$items = get_widget_items( new WP_Query( $query_args ) );

	printf(
		'<div id="draft-posts-widget-wrapper">
			<div id="draft-posts-widget" class="activity-block" style="padding-top: 0;">
				<ul>%s</ul>
			</div>
		</div>',
		display_items_in_widget( $items ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in display_items_in_widget().
	);
}

/**
 * Get the draft posts to display in the dashboard widget.
 *
 * @param WP_Query $query WP_Query object.
 *
 * @return array Array of draft posts.
 */
function get_widget_items( $query ) {
	$items = [];

	foreach ( $query->posts as $post ) {
		$title = get_the_title( $post );

		$item = apply_filters(
			'drafts_show_in_widget',
			[
				'ID'      => $post->ID,
				'title'   => '' !== $title ? $title : __( '(no title)', 'drafts' ),
				'date'    => wp_date( 'F j, g:ia', get_post_timestamp( $post ) ),
				'preview' => get_preview_post_link( $post ),
			]
		);

		if ( ! empty( $item ) && is_array( $item ) ) {
			$items[] = $item;
		}
	}

	return $items;
}

/**
 * Display draft posts in widget.
 *
 * @param array $items Post data.
 *
 * @return string Escaped output of post data.
 */
function display_items_in_widget( $items ) {
	$output = '';

	foreach ( $items as $item ) {
		$output .= sprintf(
			'<li><em style="%4$s">%1$s</em> <a href="%2$s">%3$s</a></li>',
			esc_html( $item['date'] ?? '' ),
			esc_url( $item['preview'] ?? '' ),
			esc_html( $item['title'] ?? '' ),
			'display: inline-block; margin-right: 5px; min-width: 125px; color: #646970;'
		);
	}

	return $output;
}
