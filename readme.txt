=== Pronamic Domain Mapping ===
Contributors: pronamic, remcotolsma
Tags: domain, mapping, landingpage, landingspage, page, dns, map, seo
Donate link: http://pronamic.eu/donate/?for=wp-plugin-pronamic-domain-mapping&source=wp-plugin-readme-txt
Requires at least: 3.2
Tested up to: 3.5.1
Stable tag: 1.0.0
License: GPLv2 or later

The Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.

== Description ==

With the Pronamic Domain Mapping plugin you can easily publish a page on the 
domain aliases or pointers of your hosting solution. This way you can easily 
publish landinspages on the extra domain names you own.

= WordPress Network =

If you work with an WordPress Network and want to enable domain pages you have
to add the following line to your /wp-content/sunrise.php file.

```
$file = WP_CONTENT_DIR . '/plugins/pronamic-domain-mapping/sunrise.php'; 
if ( is_readable( $file ) ) {
	include $file;
}
```


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your
WordPress installation and then activate the Plugin from Plugins page.


== Frequently Asked Questions ==

Have a question? Make a thread in the support forum and we will get back to you.


== Screenshots ==

1.	Domain Name pages overview
2.	Edit domain name page


== Changelog ==

= 1.0.0 =
*	Added support for WordPress network/multisite installations
*	Added support for the [WordPress SEO by Yoast](http://wordpress.org/plugins/wordpress-seo/) plugin
*	Added support for the [Google Analytics for WordPress](http://wordpress.org/plugins/google-analytics-for-wordpress/) plugin

= 0.1.3 =
*	Added support for The WordPress Multilingual Plugin (http://wpml.org/)

= 0.1.2 =
*	Added some screenshots

= 0.1.1 =
*	Fixed notice missing argument

= 0.1 =
*	Initial release


== Upgrade Notice ==

= 1.0.0 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.

= 0.1.3 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.

= 0.1.2 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.

= 0.1.1 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.

= 0.1 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.


== Inspiration ==
*	https://github.com/deniaz/wp-dms
