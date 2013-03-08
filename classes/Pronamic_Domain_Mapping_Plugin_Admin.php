<?php

/**
 * Pronamic Domain Mapping plugin admin
 */
class Pronamic_Domain_Mapping_Plugin_Admin {
	/**
	 * Plugin
	 *
	 * @var Pronamic_Events_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Pronamic Events plugin admin object
	 */
	public function __construct( Pronamic_Domain_Mapping_Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save_post' ) );

		// Post type
		$post_type = 'pronamic_domain_page';
		
		add_filter( "manage_edit-{$post_type}_columns",          array( $this, 'manage_edit_columns' ) );
		add_filter( "manage_edit-{$post_type}_sortable_columns", array( $this, 'manage_edit_sortable_columns' ) );
		add_filter( "manage_{$post_type}_posts_custom_column",   array( $this, 'manage_posts_custom_column' ), 10, 2 );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu
	 */
	function admin_menu() {
		$post_type = 'pronamic_domain_page';

		add_submenu_page(
			"edit.php?post_type={$post_type}", // parent_slug
			__( 'Domain Names', 'pronamic_domain_mapping' ), // page_title
			__( 'Domain Names', 'pronamic_domain_mapping' ), // menu_title
			'read', // capability
			'pronamic_domain_mapping_names', // menu_slug
			array( $this, 'page_domain_names' ) // function
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Page domain names
	 */
	function page_domain_names() {
		include $this->plugin->dirname . '/admin/domain-names.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Add meta boxes
	 */
	function add_meta_boxes() {
		$post_types = get_post_types( '', 'names' );
	
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'pronamic_domain_mapping' ) ) {
				add_meta_box(
					'pronamic_domain_mapping',
					__( 'Domain Name Mapping', 'pronamic_domain_mapping' ),
					array( $this, 'details_meta_box' ),
					$post_type,
					'normal',
					'high'
				);
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Details meta box
	 * 
	 * @param WP_Post $post
	 */
	function details_meta_box( $post ) {
		include $this->plugin->dirname . '/admin/meta-box-domain-mapping.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Save post
	 * 
	 * @param string $post_id
	 * @param WP_Post $post
	 */
	function save_post( $post_id, $post ) {
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

	//////////////////////////////////////////////////

	/**
	 * Columns
	 *
	 * @param unknown_type $columns
	 */
	function manage_edit_columns( $columns ) {
		$new_columns = array();
	
		if( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
		}
	
		// $new_columns['thumbnail'] = __('Thumbnail', 'pronamic_companies');
	
		if( isset( $columns['title'] ) ) {
			$new_columns['title'] = $columns['title'];
		}
	
		$new_columns['pronamic_domain_mapping_host'] = __( 'Domain Name', 'pronamic_domain_mapping' );
	
		if( isset( $columns['author'] ) ) {
			$new_columns['author'] = $columns['author'];
		}
	
		if( isset( $columns['comments'] ) ) {
			$new_columns['comments'] = $columns['comments'];
		}
	
		if( isset( $columns['date'] ) ) {
			$new_columns['date'] = $columns['date'];
		}
	
		return array_merge( $new_columns, $columns );
	}

	//////////////////////////////////////////////////

	/**
	 * Manage edit sortable columns
	 * 
	 * @param array $columns
	 * @return array
	 */
	function manage_edit_sortable_columns( $columns ) {
		$columns['pronamic_domain_mapping_host'] = 'pronamic_domain_mapping_host';
	
		return $columns;
	}

	//////////////////////////////////////////////////

	/**
	 * Manage posts custom column
	 * 
	 * @param string $column
	 * @param string $post_id
	 */
	function manage_posts_custom_column( $column, $post_id ) {
		switch ( $column ) {
			case "pronamic_domain_mapping_host":
				$host = get_post_meta( $post_id, '_pronamic_domain_mapping_host', true);
				$url = 'http://' . $host . '/';
	
				echo '<a href="' . $url . '">' . $host. '</a>';
	
				break;
		}
	}
}
