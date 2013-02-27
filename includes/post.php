<?php

function pronamic_domain_mapping_create_initial_post_types() {
	register_post_type(
		'pronamic_domain_post',
		array(
			'label'         => __( 'Domain Name Post', 'pronamic_domain_mapping' ),
			'public'        => true,
			'menu_position' => 30,
			// 'menu_icon'     => $orbis_hosting_plugin->plugin_url( 'admin/images/hosting_group.png' ),
			'supports'      => array( 'title', 'editor', 'comments' ),
			'rewrite'       => array( 'slug' => _x( 'domain-name', 'slug', 'pronamic_domain_mapping' ) ) 
		)
	);
}

add_action( 'init', 'pronamic_domain_mapping_create_initial_post_types', 0 ); // highest priority
