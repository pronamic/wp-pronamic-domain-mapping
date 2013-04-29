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
	 * Domain page ID
	 * 
	 * @var int
	 */
	private $domain_page_id;

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

		$this->set_domain_page_id();

		// Actions
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// Filters
		add_filter( 'request', array( $this, 'request' ), 1 );
		add_filter( 'request', array( $this, 'request_orderby' ) );
		
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 10, 2 );

		// WPML
		add_filter( 'icl_set_current_language', array( $this, 'icl_set_current_language' ) );

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
	 * Set domain page ID based upon the HTTP host
	 */
	private function set_domain_page_id() {
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
		
		$this->domain_page_id = $wpdb->get_var( $db_query );
	}

	//////////////////////////////////////////////////

	/**
	 * Request
	 * 
	 * @param array $args
	 * @return array
	 */
	public function request( $args ) {	
		if ( ! empty( $this->domain_page_id ) ) {
			$args['post_type'] = 'any';
			$args['p']         = $this->domain_page_id;
		}

		return $args;
	}

	//////////////////////////////////////////////////
	
	/**
	 * WPML set current language
	 * 
	 * WPML has three ways of determining the current language 'language 
	 * negotiation type':
	 * 
	 * 1 = Different languages in directories
	 * 2 = A different domain per language
	 * 3 = Language name added as a parameter
	 * 
	 * These methods won't work with domain pages with an domain name. Therefor
	 * we use a custom method to determine the language of these pages.
	 * 
	 * @param string $langauge
	 */
	public function icl_set_current_language( $langauge ) {
		if ( ! empty( $this->domain_page_id ) ) {
			global $sitepress;

			$langauge = $sitepress->get_language_for_element( $this->domain_page_id, 'post_pronamic_domain_page' );
		}

		return $langauge;
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
