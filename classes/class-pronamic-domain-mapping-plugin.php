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
		require_once $this->dirname . '/includes/version.php';
		require_once $this->dirname . '/includes/post.php';

		// Determine the domain page ID
		$this->set_domain_page_id();

		// Actions
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'send_headers', array( $this, 'send_headers' ) );

		// Filters
		add_filter( 'request', array( $this, 'request' ), 1 );
		add_filter( 'request', array( $this, 'request_orderby' ) );

		// Links
		// @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L138-L143

		// @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L324-L333
		add_filter( 'page_link', array( $this, 'page_link' ), 10, 2 );
		// @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L275-L285
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
	 * Initialize
	 */
	public function init() {
		global $wpdb;

		$wpdb->pronamic_domain_posts = $wpdb->base_prefix . 'pronamic_domain_posts';

		if ( ! empty( $this->domain_page_id ) ) {
			// Google Analytics for WordPress
			// @version < 5.0
			// @see https://github.com/Yoast/google-analytics-for-wordpress
			global $yoast_ga;

			if ( isset( $yoast_ga ) ) {
				$ga_ua = get_post_meta( $this->domain_page_id, '_pronamic_domain_mapping_ga_ua', true );

				if ( ! empty( $ga_ua ) ) {
					$yoast_ga->options['uastring'] = $ga_ua;
					$yoast_ga->options['trackcrossdomain'] = false;
					$yoast_ga->options['primarycrossdomain'] = false;
				}
			}

			// WordPress SEO by Yoast
			// @see https://github.com/Yoast/wordpress-seo
			add_action( 'wpseo_head', array( $this, 'wpseo_head' ), 90 );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded() {
		$plugin_rel_path = dirname( plugin_basename( $this->file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_domain_mapping', false, $plugin_rel_path );
	}

	//////////////////////////////////////////////////

	/**
	 * Send headers
	 */
	public function send_headers() {
		if ( ! empty( $this->domain_page_id ) ) {
			// Access-Control-Allow-Origin
			// @see http://stackoverflow.com/a/4110601
			$url = get_site_url();

			$host = parse_url( $url, PHP_URL_HOST );

			if ( false !== $host ) {
				header( 'Access-Control-Allow-Origin: '. $host );
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Set domain page ID based upon the HTTP host
	 */
	private function set_domain_page_id() {
		global $wpdb;
		global $pronamic_domain_mapping_sunrise_host;

		if ( isset( $pronamic_domain_mapping_sunrise_host ) ) {
			$host = $pronamic_domain_mapping_sunrise_host;
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}

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

		if ( ! empty( $this->domain_page_id ) ) {
			// @see https://github.com/WordPress/WordPress/blob/4.0/wp-includes/option.php#L112-L123
			// @see https://github.com/Yoast/google-analytics-for-wordpress/blob/5.1/includes/class-options.php#L9-L14
			add_filter( 'option_yst_ga', array( $this, 'option_yst_ga' ) );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Option Yoast Google Analytics
	 *
	 * @version > 5.0
	 * @param $options
	 * @return array
	 */
	public function option_yst_ga( $options ) {
		if ( is_array( $options ) && isset( $options['ga_general'] ) ) {
			$ga_ua = get_post_meta( $this->domain_page_id, '_pronamic_domain_mapping_ga_ua', true );

			if ( ! empty( $ga_ua ) ) {
				$options['ga_general']['manual_ua_code']       = true;
				$options['ga_general']['manual_ua_code_field'] = $ga_ua;
			}
		}

		return $options;
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
				'orderby'  => 'meta_value',
			) );
		}

		return $args;
	}

	//////////////////////////////////////////////////

	/**
	 * Page link
	 *
	 * @param string $link
	 * @param int $post_id
	 */
	public function page_link( $link, $post_id ) {
		return $this->post_type_link( $link, get_post( $post_id ) );
	}

	/**
	 * Link
	 *
	 * @param string $permalink
	 * @param WP_Post $post
	 * @param boolean $leavename
	 */
	public function post_type_link( $link, $post ) {
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

	//////////////////////////////////////////////////

	/**
	 * WordPress SEO by Yoast head
	 *
	 * @see https://github.com/Yoast/wordpress-seo/blob/b4c0e52e02cd850e753412f4e9a435b509df360f/frontend/class-frontend.php#L481
	 */
	public function wpseo_head() {
		global $wpseo_front;

		if ( isset( $wpseo_front ) && ! empty( $this->domain_page_id ) ) {
			if ( ! empty( $wpseo_front->options['googleverify'] ) ) {
				$google_meta = $wpseo_front->options['googleverify'];
				if ( strpos( $google_meta, 'content' ) ) {
					preg_match( '`content="([^"]+)"`', $google_meta, $match );
					$google_meta = $match[1];
				}

				printf(
					'<meta name="google-site-verification" content="%s" />',
					esc_attr( $google_meta )
				);

				echo "\n";
			}

			if ( ! empty( $wpseo_front->options['msverify'] ) ) {
				$bing_meta = $wpseo_front->options['msverify'];
				if ( strpos( $bing_meta, 'content' ) ) {
					preg_match( '`content="([^"]+)"`', $bing_meta, $match );
					$bing_meta = $match[1];
				}

				printf(
					'<meta name="msvalidate.01" content="%s" />',
					esc_attr( $bing_meta )
				);

				echo "\n";
			}

			if ( ! empty( $wpseo_front->options['alexaverify'] ) ) {
				printf(
					'<meta name="alexaVerifyID" content="%s" />',
					esc_attr( $wpseo_front->options['alexaverify'] )
				);

				echo "\n";
			}
		}
	}
}
