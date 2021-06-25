# [Pronamic Domain Mapping](https://www.pronamic.eu/plugins/pronamic-domain-mapping/)

**Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.**

[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](https://gruntjs.com/)

## Installation

### WordPress Network

If you work with an WordPress Network and want to enable domain pages you have
to add the following line to your `/wp-content/sunrise.php` file.

```php
$file = WP_CONTENT_DIR . '/plugins/pronamic-domain-mapping/sunrise.php'; 
if ( is_readable( $file ) ) {
	include $file;
}
```

Additionally, in order for `sunrise.php` to be loaded, you must add the following to your `wp-config.php`:

```php
define( 'SUNRISE', true );
```

## Post type support

Aside from the custom post type included in this plugin, domain mapping support can also be added
to other post types. For example, to add support to the `page` post type: 

```php
add_post_type_support( 'page', 'pronamic_domain_mapping' );
```

# Links

*	https://github.com/deniaz/wp-dms
*	https://github.com/humanmade/Mercator
