<?php
/**
 * Plugin Name: Pronamic Domain Mapping
 * Plugin URI: https://www.pronamic.eu/plugins/pronamic-domain-mapping/
 * Description: The Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.
 *
 * Version: 2.0.2
 * Requires at least: 3.2
 *
 * Author: Pronamic
 * Author URI: https://www.pronamic.eu/
 *
 * Text Domain: pronamic_domain_mapping
 * Domain Path: /languages/
 *
 * License: GPL
 *
 * GitHub URI: https://github.com/pronamic/wp-pronamic-domain-mapping
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

// Autoloader.
require __DIR__ . '/vendor/autoload.php';

/**
 * Bootstrap.
 */
global $pronamic_domain_mapping_plugin;

$pronamic_domain_mapping_plugin = \Pronamic\WordPress\DomainMapping\Plugin::instance(
	array(
		'file' => __FILE__,
	)
);
