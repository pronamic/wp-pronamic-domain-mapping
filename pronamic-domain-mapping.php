<?php
/*
Plugin Name: Pronamic Domain Mapping
Plugin URI: http://pronamic.eu/wp-plugins/domain-mapping/
Description: The Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.

Version: 0.1
Requires at least: 3.2

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_domain_mapping
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-domain-mapping
*/

require_once dirname( __FILE__ ) . '/classes/Pronamic_Domain_Mapping_Plugin.php';
require_once dirname( __FILE__ ) . '/classes/Pronamic_Domain_Mapping_Plugin_Admin.php';

global $pronamic_domain_mapping_plugin;

$pronamic_domain_mapping_plugin = new Pronamic_Domain_Mapping_Plugin( __FILE__ );
