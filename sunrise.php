<?php

/**
 * Sunrise
 *
 * @see https://github.com/WordPress/WordPress/blob/3.6.1/wp-includes/ms-settings.php#L17
 *
 * @see http://plugins.trac.wordpress.org/browser/wordpress-mu-domain-mapping/tags/0.5.4.3/sunrise.php
 */
global $wpdb;

// Tables
$wpdb->pronamic_domain_posts = $wpdb->base_prefix . 'pronamic_domain_posts';

// Host
$host = $_SERVER['HTTP_HOST'];

// Query
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

$query = $wpdb->prepare( $query, $host );

$blog_host = $wpdb->get_var( $query );

if ( ! empty( $blog_host ) ) {
	$pronamic_domain_mapping_sunrise_host = $host;

	$_SERVER['HTTP_HOST'] = $blog_host;
}
