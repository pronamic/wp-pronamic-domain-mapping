# [Pronamic Domain Mapping](http://www.pronamic.eu/plugins/pronamic-domain-mapping/)

**Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.**

[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)

## Installation

### WordPress Network

If you work with an WordPress Network and want to enable domain pages you have
to add the following line to your /wp-content/sunrise.php file.

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

# Links

*	https://github.com/deniaz/wp-dms
*	https://github.com/humanmade/Mercator
