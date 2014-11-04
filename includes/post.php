<?php

function pronamic_domain_mapping_create_initial_post_types() {
	register_post_type(
		'pronamic_domain_page',
		array(
			'label'           => __( 'Domain Name Page', 'pronamic_domain_mapping' ),
			'labels' => array(
				'name'               => _x( 'Domain Name Pages', 'post type general name', 'pronamic_domain_mapping' ),
				'singular_name'      => _x( 'Domain Name Page', 'post type singular name', 'pronamic_domain_mapping' ),
				'add_new'            => _x( 'Add New', 'pronamic_domain_page', 'pronamic_domain_mapping' ),
				'add_new_item'       => __( 'Add New Domain Name Page', 'pronamic_domain_mapping' ),
				'edit_item'          => __( 'Edit Domain Name Page', 'pronamic_domain_mapping' ),
				'new_item'           => __( 'New Domain Name Page', 'pronamic_domain_mapping' ),
				'view_item'          => __( 'View Domain Name Page', 'pronamic_domain_mapping' ),
				'search_items'       => __( 'Search Domain Name Pages', 'pronamic_domain_mapping' ),
				'not_found'          => __( 'No domain name pages found', 'pronamic_domain_mapping' ),
				'not_found_in_trash' => __( 'No domain name pages found in Trash', 'pronamic_domain_mapping' ),
				'parent_item_colon'  => __( 'Parent Domain Name Page:', 'pronamic_domain_mapping' ),
				'menu_name'          => __( 'Domain Name Pages', 'pronamic_domain_mapping' )
			),
			'public'          => true,
			'menu_position'   => 30,
			'capability_type' => 'page',
			'supports'        => array( 'title', 'editor', 'author', 'comments', 'revisions', 'pronamic_domain_mapping' ),
			'rewrite'         => array( 'slug' => _x( 'domain-name-page', 'slug', 'pronamic_domain_mapping' ) )
		)
	);
}

add_action( 'init', 'pronamic_domain_mapping_create_initial_post_types', 0 ); // highest priority
