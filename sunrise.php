<?php

/**
 * Sunrise
 *
 * @see https://github.com/WordPress/WordPress/blob/3.6.1/wp-includes/ms-settings.php#L17
 *
 * @see http://plugins.trac.wordpress.org/browser/wordpress-mu-domain-mapping/tags/0.5.4.3/sunrise.php
 */
global $wpdb;
global $pronamic_domain_mapping_sunrise_host;

// Tables
// @see https://github.com/markoheijnen/wordpress-mu-domain-mapping/blob/master/sunrise.php#L10
$wpdb->domain_mapping        = $wpdb->base_prefix . 'domain_mapping';
$wpdb->pronamic_domain_posts = $wpdb->base_prefix . 'pronamic_domain_posts';

// Host
$host = $_SERVER['HTTP_HOST'];

$blog_host = null;

// WordPress MU Domain Mapping
if ( null === $blog_host ) {
	$query = "
		SELECT
			dm.domain
		FROM
			$wpdb->domain_mapping AS dm
				LEFT JOIN
			$wpdb->pronamic_domain_posts AS pdp
					ON dm.blog_id = pdp.blog_id AND active = 1
		WHERE
			pdp.domain = %s
		;
	";

	$query = $wpdb->prepare( $query, $host ); // WPCS: unprepared SQL ok.

	$blog_host = $wpdb->get_var( $query ); // WPCS: unprepared SQL ok.
}

// WordPress Core
if ( null === $blog_host ) {
	$query = "
		SELECT
			blog.domain
		FROM
			$wpdb->blogs AS blog
				LEFT JOIN
			$wpdb->pronamic_domain_posts AS pdp
					ON blog.blog_id = pdp.blog_id
		WHERE
			pdp.domain = %s
		;
	";

	$query = $wpdb->prepare( $query, $host ); // WPCS: unprepared SQL ok.

	$blog_host = $wpdb->get_var( $query ); // WPCS: unprepared SQL ok.
}

// Fake WordPress
if ( ! empty( $blog_host ) ) {
	$pronamic_domain_mapping_sunrise_host = $host;

	$_SERVER['HTTP_HOST'] = $blog_host;
}
