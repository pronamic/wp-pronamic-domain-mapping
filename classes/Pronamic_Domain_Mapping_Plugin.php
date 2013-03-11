<?php

/**
 * Pronamic Domain Mapping plugin
 */
class Pronamic_Domain_Mapping_Plugin {
	/**
	 * Plugin file
	 *
	 * @var string
	 */
	public $file;

	/**
	 * Plugin directory name
	 *
	 * @var string
	 */
	public $dirname;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Pronamic Events plugin
	 *
	 * @param string $file the plugin file
	 */
	public function __construct( $file ) {
		$this->file    = $file;
		$this->dirname = dirname( $file );

		// Includes
		require_once $this->dirname . '/includes/post.php';

		// Actions
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// Filters
		add_filter( 'request', array( $this, 'request' ), 1 );
		add_filter( 'request', array( $this, 'request_orderby' ) );
		
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 10, 2 );

		// Admin
		if ( is_admin() ) {
			new Pronamic_Domain_Mapping_Plugin_Admin( $this );
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Plugins loaded
	 */
	function plugins_loaded() {
		$plugin_rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_domain_mapping', false, $plugin_rel_path );
	}

	//////////////////////////////////////////////////

	/**
	 * Request
	 * 
	 * @param array $args
	 * @return array
	 */
	function request( $args ) {	
		global $wpdb;

		$host = $_SERVER['HTTP_HOST'];
	
		$db_query = $wpdb->prepare( "
			SELECT
				post_id
			FROM
				$wpdb->postmeta
			WHERE
				meta_key = '_pronamic_domain_mapping_host'
					AND
				meta_value = %s
			;
		", $host );
	
		$post_id = $wpdb->get_var( $db_query );
	
		if ( ! empty( $post_id) ) {
			$args['post_type'] = 'any';
			$args['p']         = $post_id;
		}
	
		return $args;
	}

	//////////////////////////////////////////////////

	/**
	 * Request order by
	 * 
	 * @param array $args
	 * @return array
	 */
	public function request_orderby( $args ) {
		// Order by
		if ( isset( $args['orderby'] ) && 'pronamic_domain_mapping_host' == $args['orderby'] ) {
			$args = array_merge( $args, array(
					'meta_key' => '_pronamic_domain_mapping_host',
					'orderby' => 'meta_value'
			) );
		}
		
		return $args;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Link
	 *
	 * @param string $permalink
	 * @param WP_Post $post
	 * @param boolean $leavename
	 */
	function post_type_link( $link, $post ) {
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
}
