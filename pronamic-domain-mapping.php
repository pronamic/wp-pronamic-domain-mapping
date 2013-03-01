<?php
/*
Plugin Name: Pronamic Domain Mapping
Plugin URI: http://pronamic.eu/wp-plugins/domain-mapping/
Description: 

Version: 0.1
Requires at least: 3.2

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic-domain-mapping
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-domain-mapping
*/

global $pronamic_domain_mapping_file;
global $pronamic_domain_mapping_dirname;

$pronamic_domain_mapping_file    = __FILE__;
$pronamic_domain_mapping_dirname = dirname( __FILE__ );

include $pronamic_domain_mapping_dirname . '/includes/post.php';

/**
 * Initialize
 */
function pronamic_domain_mapping_reqest( $args ) {
	$host = $_SERVER['HTTP_HOST'];

	global $wpdb;
	
	$db_query = $wpdb->prepare( "
		SELECT
			post_id 
		FROM
			$wpdb->postmeta 
		WHERE
			meta_key = '_pronamic_domain_mapping_host'
				AND
			meta_value = %s
	", $host );

	$post_id = $wpdb->get_var( $db_query );

	if ( ! empty( $post_id) ) {
		$args['post_type'] = 'any';
		$args['p']         = $post_id;
	}
	
	return $args;
}

add_filter( 'request', 'pronamic_domain_mapping_reqest', 1 );

/**
 * Loaded
 */
function pronamic_domain_mapping_loaded() {
	$plugin_rel_path = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	
	load_plugin_textdomain( 'pronamic_domain_mapping', false, $plugin_rel_path );
}

add_action( 'plugins_loaded', 'pronamic_domain_mapping_loaded' );

/**
 * Link
 * 
 * @param string $permalink
 * @param WP_Post $post
 * @param boolean $leavename
 */
function pronamic_domain_mapping_link( $link, $post ) {
	// This is also required to prevent 'redirect_canonical'
	// @see http://www.mydigitallife.info/how-to-disable-wordpress-canonical-url-or-permalink-auto-redirect/
	if ( post_type_supports( $post->post_type, 'pronamic_domain_mapping' ) ) {
		$host = get_post_meta( $post->ID, '_pronamic_domain_mapping_host', true );

		if ( ! empty( $host ) ) {
			$link = 'http://' . $host . '/';
		}
	}
	
	return $link;
}

add_filter( 'post_type_link', 'pronamic_domain_mapping_link', 10, 2 );

/**
 * Admin menu
 */
function pronamic_domain_mapping_admin_menu() {
	add_submenu_page(
		'edit.php?post_type=pronamic_domain_page', // parent_slug
		__( 'Domain Names', 'pronamic_domain_mapping' ), // page_title
		__( 'Domain Names', 'pronamic_domain_mapping' ), // menu_title
		'read', // capability
		'pronamic_domain_mapping_names', // menu_slug
		'pronamic_domain_mapping_names_page' // function
	);
}

add_action( 'admin_menu', 'pronamic_domain_mapping_admin_menu' );

/**
 * Domain names page
 */
function pronamic_domain_mapping_names_page() {
	include dirname( __FILE__ ) . '/admin/domain-names.php';
}