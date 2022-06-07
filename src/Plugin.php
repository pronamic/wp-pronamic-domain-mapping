<?php
/**
 * Plugin
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\DomainMapping
 */

namespace Pronamic\WordPress\DomainMapping;

use WP_Post;

/**
 * Pronamic Domain Mapping plugin.
 *
 * @author  Remco Tolsma
 * @version 2.0.2
 * @since   1.0.0
 */
class Plugin {
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

	/**
	 * Domain page ID
	 *
	 * @var int
	 */
	private $domain_page_id;

	/**
	 * Instance.
	 *
	 * @var Plugin|null
	 */
	protected static $instance;

	/**
	 * Instance.
	 *
	 * @param string|array|object $args The plugin arguments.
	 *
	 * @return Plugin
	 */
	public static function instance( $args = array() ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self( $args );
		}

		return self::$instance;
	}

	/**
	 * Constructs and initializes an Pronamic Events plugin
	 *
	 * @param array $args The plugin arguments.
	 * @return void
	 */
	public function __construct( $args ) {
		$this->file    = $args['file'];
		$this->dirname = dirname( $this->file );

		// Includes.
		require_once $this->dirname . '/includes/version.php';
		require_once $this->dirname . '/includes/post.php';

		// Determine the domain page ID.
		$this->set_domain_page_id();

		// Actions.
		\add_action( 'init', array( $this, 'init' ) );

		\add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		\add_action( 'send_headers', array( $this, 'send_headers' ) );

		// Filters.
		\add_filter( 'request', array( $this, 'request' ), 1 );
		\add_filter( 'request', array( $this, 'request_orderby' ) );

		/*
		 * Links.
		 *
		 * @link https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L138-L143
		 */

		// @link https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L324-L333
		\add_filter( 'page_link', array( $this, 'page_link' ), 10, 2 );

		// @link https://github.com/WordPress/WordPress/blob/4.0/wp-includes/link-template.php#L275-L285
		\add_filter( 'post_type_link', array( $this, 'post_type_link' ), 10, 2 );

		// @link https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/default-filters.php#L460
		\add_action( 'template_redirect', array( $this, 'template_redirect' ), 1 );

		// @link https://github.com/WordPress/WordPress/blob/4.4/wp-includes/canonical.php#L479-L489
		\add_filter( 'redirect_canonical', array( $this, 'redirect_canonical' ), 10, 2 );

		// WPML.
		\add_filter( 'icl_set_current_language', array( $this, 'icl_set_current_language' ) );

		// WordPress SEO.
		\add_filter( 'wpseo_sitemap_exclude_post_type', array( $this, 'wpseo_sitemap_exclude_post_type' ), 10, 2 );

		// Admin.
		if ( \is_admin() ) {
			new Admin( $this );
		}
	}

	/**
	 * Initialize.
	 */
	public function init() {
		global $wpdb;

		$wpdb->pronamic_domain_posts = $wpdb->base_prefix . 'pronamic_domain_posts';

		if ( ! empty( $this->domain_page_id ) ) {
			/*
			 * Google Analytics for WordPress.
			 *
			 * @version <5.0
			 * @link https://github.com/Yoast/google-analytics-for-wordpress
			 */
			global $yoast_ga;

			if ( isset( $yoast_ga ) ) {
				$ga_ua = \get_post_meta( $this->domain_page_id, '_pronamic_domain_mapping_ga_ua', true );

				if ( ! empty( $ga_ua ) ) {
					$yoast_ga->options['uastring']           = $ga_ua;
					$yoast_ga->options['trackcrossdomain']   = false;
					$yoast_ga->options['primarycrossdomain'] = false;
				}
			}

			/*
			 * WordPress SEO by Yoast.
			 *
			 * @link https://github.com/Yoast/wordpress-seo
			 */
			\add_action( 'wpseo_head', array( $this, 'wpseo_head' ), 90 );
		}
	}

	/**
	 * Plugins loaded.
	 *
	 * @return void
	 */
	public function plugins_loaded() {
		$plugin_rel_path = \dirname( \plugin_basename( $this->file ) ) . '/languages/';

		\load_plugin_textdomain( 'pronamic_domain_mapping', false, $plugin_rel_path );
	}

	/**
	 * Send headers.
	 *
	 * @return void
	 */
	public function send_headers() {
		if ( empty( $this->domain_page_id ) ) {
			return;
		}

		/*
		 * Access-Control-Allow-Origin.
		 *
		 * @link https://stackoverflow.com/a/4110601
		 */
		$url = \get_site_url();

		$host = \wp_parse_url( $url, PHP_URL_HOST );

		if ( false !== $host ) {
			\header( 'Access-Control-Allow-Origin: ' . $host );
		}
	}

	/**
	 * Set domain page ID based upon the HTTP host.
	 *
	 * @return void
	 */
	private function set_domain_page_id() {
		global $wpdb;
		global $pronamic_domain_mapping_sunrise_host;

		$host = null;

		if ( \array_key_exists( 'HTTP_HOST', $_SERVER ) ) {
			$host = \filter_var( \wp_unslash( $_SERVER['HTTP_HOST'] ), \FILTER_SANITIZE_STRING );
		}

		if ( isset( $pronamic_domain_mapping_sunrise_host ) ) {
			$host = $pronamic_domain_mapping_sunrise_host;
		}

		$this->domain_page_id = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT
					post_id
				FROM
					$wpdb->postmeta
				WHERE
					meta_key = '_pronamic_domain_mapping_host'
						AND
					meta_value = %s
				;",
				$host
			)
		);

		if ( ! empty( $this->domain_page_id ) ) {
			// @link https://github.com/WordPress/WordPress/blob/4.0/wp-includes/option.php#L112-L123
			// @link https://github.com/Yoast/google-analytics-for-wordpress/blob/5.1/includes/class-options.php#L9-L14
			add_filter( 'option_yst_ga', array( $this, 'option_yst_ga' ) );
		}
	}

	/**
	 * Option Yoast Google Analytics.
	 *
	 * @version > 5.0
	 * @param array $options Options.
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

	/**
	 * Request.
	 *
	 * @param array $args Arguments.
	 * @return array
	 */
	public function request( $args ) {
		if ( ! empty( $this->domain_page_id ) ) {
			$args['post_type'] = 'any';
			$args['p']         = $this->domain_page_id;
		}

		return $args;
	}

	/**
	 * WPML set current language.
	 *
	 * WPML has three ways of determining the current language 'language
	 * negotiation type':
	 *
	 * 1 = Different languages in directories
	 * 2 = A different domain per language
	 * 3 = Language name added as a parameter
	 *
	 * These methods won't work with domain pages with an domain name. Therefore
	 * we use a custom method to determine the language of these pages.
	 *
	 * @param string $language Language.
	 * @return string
	 */
	public function icl_set_current_language( $language ) {
		if ( ! empty( $this->domain_page_id ) ) {
			global $sitepress;

			$language = $sitepress->get_language_for_element( $this->domain_page_id, 'post_' . \get_post_type( $this->domain_page_id ) );
		}

		return $language;
	}

	/**
	 * Request order by.
	 *
	 * @param array $args Arguments.
	 * @return array
	 */
	public function request_orderby( $args ) {
		// Order by.
		if ( isset( $args['orderby'] ) && 'pronamic_domain_mapping_host' === $args['orderby'] ) {
			$args = \array_merge(
				$args,
				array(
					'meta_key' => '_pronamic_domain_mapping_host',
					'orderby'  => 'meta_value',
				)
			);
		}

		return $args;
	}

	/**
	 * Page link.
	 *
	 * @param string $link    Link.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	public function page_link( $link, $post_id ) {
		return $this->post_type_link( $link, \get_post( $post_id ) );
	}

	/**
	 * Link.
	 *
	 * @param string  $link Link.
	 * @param WP_Post $post Post.
	 * @return string
	 * @link https://github.com/WordPress/WordPress/blob/4.4/wp-includes/link-template.php#L283-L293
	 */
	public function post_type_link( $link, $post ) {
		// To view draft posts you have to be logged in, the auth cookies are most of the time
		// not set on the domain name page, therefore we return the default post link.
		if ( 'draft' === $post->post_status ) {
			return $link;
		}

		/*
		 * This is also required to prevent 'redirect_canonical'.
		 *
		 * @link https://www.mydigitallife.info/how-to-disable-wordpress-canonical-url-or-permalink-auto-redirect/
		 */
		if ( \post_type_supports( $post->post_type, 'pronamic_domain_mapping' ) ) {
			$host = \get_post_meta( $post->ID, '_pronamic_domain_mapping_host', true );

			if ( ! empty( $host ) ) {
				$protocol = \get_post_meta( $post->ID, '_pronamic_domain_mapping_protocol', true );
				$protocol = empty( $protocol ) ? 'http' : $protocol;

				$link = $protocol . '://' . $host . '/';
			}
		}

		return $link;
	}

	/**
	 * Template redirect filter.
	 *
	 * @return void
	 */
	public function template_redirect() {
		global $post;

		if ( ! empty( $this->domain_page_id ) ) {
			return;
		}

		if ( ! \is_single() || ! \post_type_supports( $post->post_type, 'pronamic_domain_mapping' ) || 'publish' !== $post->post_status ) {
			return;
		}

		$this->domain_page_id = \get_the_ID();

		// @link https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/canonical.php#L167
		$_GET['p'] = $this->domain_page_id;
	}

	/**
	 * Redirect canonical.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/4.4/wp-includes/canonical.php#L479-L489
	 *
	 * @param string $redirect_url  Redirect URL.
	 * @param string $requested_url Requested URL.
	 *
	 * @return bool|string
	 */
	public function redirect_canonical( $redirect_url, $requested_url ) {
		global $pronamic_domain_mapping_sunrise_host;

		if ( ! empty( $this->domain_page_id ) ) {
			$redirect_url = \get_permalink( $this->domain_page_id );
		}

		if ( isset( $pronamic_domain_mapping_sunrise_host ) ) {
			// @link https://github.com/WordPress/WordPress/blob/4.4/wp-includes/canonical.php#L62-L67
			$requested_url  = \is_ssl() ? 'https://' : 'http://';
			$requested_url .= $pronamic_domain_mapping_sunrise_host;

			if ( \array_key_exists( 'REQUEST_URI', $_SERVER ) ) {
				$requested_url .= filter_var( \wp_unslash( $_SERVER['REQUEST_URI'] ), \FILTER_SANITIZE_STRING );
			}
		}

		// @link https://github.com/WordPress/WordPress/blob/4.4/wp-includes/canonical.php#L465-L467
		if ( $redirect_url === $requested_url ) {
			$redirect_url = false;
		}

		return $redirect_url;
	}

	/**
	 * WordPress SEO by Yoast head.
	 *
	 * @link https://github.com/Yoast/wordpress-seo/blob/b4c0e52e02cd850e753412f4e9a435b509df360f/frontend/class-frontend.php#L481
	 */
	public function wpseo_head() {
		global $wpseo_front;

		if ( ! isset( $wpseo_front ) || empty( $this->domain_page_id ) ) {
			return;
		}

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

	/**
	 * WordPress SEO.
	 *
	 * Exclude `pronamic_domain_page` post type from sitemap index,
	 * as the sitemap for this post type will result in 404's due to external URLs.
	 *
	 * @link https://github.com/Yoast/wordpress-seo/blob/3.2.5/inc/sitemaps/class-post-type-sitemap-provider.php#L413-L420
	 * @link https://github.com/Yoast/wordpress-seo/blob/3.2.5/inc/sitemaps/class-post-type-sitemap-provider.php#L204-L212
	 *
	 * @param bool   $exclude   Flag to indicate whether or not to exclude post type.
	 * @param string $post_type Post type.
	 * @return bool
	 */
	public function wpseo_sitemap_exclude_post_type( $exclude, $post_type ) {
		if ( 'pronamic_domain_page' === $post_type ) {
			$exclude = true;
		}

		return $exclude;
	}
}
