<?php

function pronamic_domain_mapping_create_initial_post_types() {
	register_post_type(
		'pronamic_domain_page',
		array(
			'label'           => __( 'Domain Name Page', 'pronamic_domain_mapping' ),
			'labels' => array(
				'name' => _x( 'Domain Name Pages', 'post type general name', 'pronamic_domain_mapping' ),
				'singular_name' => _x( 'Domain Name Page', 'post type singular name', 'pronamic_domain_mapping' ),
				'add_new' => _x( 'Add New', 'pronamic_domain_page', 'pronamic_domain_mapping' ),
				'add_new_item' => __( 'Add New Domain Name Page', 'pronamic_domain_mapping' ),
				'edit_item' => __( 'Edit Domain Name Page', 'pronamic_domain_mapping' ),
				'new_item' => __( 'New Domain Name Page', 'pronamic_domain_mapping' ),
				'view_item' => __( 'View Domain Name Page', 'pronamic_domain_mapping' ),
				'search_items' => __( 'Search Domain Name Pages', 'pronamic_domain_mapping' ),
				'not_found' => __( 'No domain name pages found', 'pronamic_domain_mapping' ),
				'not_found_in_trash' => __( 'No domain name pages found in Trash', 'pronamic_domain_mapping' ),
				'parent_item_colon' => __( 'Parent Domain Name Page:', 'pronamic_domain_mapping' ),
				'menu_name' => __( 'Domain Name Pages', 'pronamic_domain_mapping' )
			),
			'public'          => true,
			'menu_position'   => 30,
			'capability_type' => 'page',
			// 'menu_icon'     => $orbis_hosting_plugin->plugin_url( 'admin/images/hosting_group.png' ),
			'supports'        => array( 'title', 'editor', 'comments', 'pronamic_domain_mapping' ),
			'rewrite'         => array( 'slug' => _x( 'domain-name-page', 'slug', 'pronamic_domain_mapping' ) ) 
		)
	);
}

add_action( 'init', 'pronamic_domain_mapping_create_initial_post_types', 0 ); // highest priority

/**
 * Add domain mapping meta boxes
 */
function pronamic_domain_mapping_add_meta_boxes() {
	$post_types = get_post_types( '','names' );

	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'pronamic_domain_mapping' ) ) {
			add_meta_box(
				'pronamic_domain_mapping',
				__( 'Domain Mapping', 'pronamic_domain_mapping' ),
				'pronamic_domain_mapping_details_meta_box',
				$post_type,
				'normal',
				'high'
			);
		}
	}
}

add_action( 'add_meta_boxes', 'pronamic_domain_mapping_add_meta_boxes' );

/**
 * Domain mapping details meta box
 *
 * @param array $post
*/
function pronamic_domain_mapping_details_meta_box( $post ) {
	global $pronamic_domain_mapping_dirname;
	
	include $pronamic_domain_mapping_dirname . '/admin/meta-box-domain-mapping.php';
}

/**
 * Save domain mapping details
 */
function pronamic_domain_mappin_save_details( $post_id, $post ) {
	// Doing autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Verify nonce
	$nonce = filter_input( INPUT_POST, 'pronamic_domain_mapping_meta_box_nonce', FILTER_SANITIZE_STRING );
	if ( ! wp_verify_nonce( $nonce, 'pronamic_domain_mapping_save' ) ) {
		return;
	}

	// Check permissions
	if ( ! ( post_type_supports( $post->post_type, 'pronamic_domain_mapping' ) && current_user_can( 'edit_post', $post_id ) ) ) {
		return;
	}

	// OK
	$definition = array(
		'_pronamic_domain_mapping_host' => FILTER_SANITIZE_STRING
	);

	$data = filter_input_array( INPUT_POST, $definition );

	foreach ( $data as $key => $value ) {
		if ( empty( $value ) ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}
}

add_action( 'save_post', 'pronamic_domain_mappin_save_details', 10, 2 );
