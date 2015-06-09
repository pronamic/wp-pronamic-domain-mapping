=== Pronamic Domain Mapping ===
Contributors: pronamic, remcotolsma
Tags: domain, mapping, landingpage, landingspage, page, dns, map, seo
Donate link: http://pronamic.eu/donate/?for=wp-plugin-pronamic-domain-mapping&source=wp-plugin-readme-txt
Requires at least: 3.2
Tested up to: 4.2.2
Stable tag: 1.2.0
License: GPLv2 or later

The Pronamic Domain Mapping plugin allows you to map domains to custom domain name pages.

== Description ==

With the Pronamic Domain Mapping plugin you can easily publish a page on the 
domain aliases or pointers of your hosting solution. This way you can easily 
publish landinspages on the extra domain names you own.

= WordPress Network =

If you work with an WordPress Network and want to enable domain pages you have
to add the following line to your /wp-content/sunrise.php file.

`
$file = WP_CONTENT_DIR . '/plugins/pronamic-domain-mapping/sunrise.php'; 
if ( is_readable( $file ) ) {
	include $file;
}
`


== Installation ==

1. Extract the ZIP-file and upload the contents to the **/wp-content/plugins/** directory of your WordPress installation
2. Activate the plugin from the *Plugins* page
3. Create a new domain name page by clicking **Add New** in the **Domain Name Pages** menu
4. Enter the domain for which this page should be shown in the field **Domain Name**

To actually visit the just added domain name page, you need to make sure the domain is configured to show your WordPress site. To test the domain name page, just visit the domain for which you're creating the domain name page. If you don't see the domain name page, follow the instructions below if your host uses either the Plesk or DirectAdmin control panel to link the domain to your main domain (which is running the WordPress installation).

= Creating a Domain Alias in Plesk =

1. Login to the Plesk control panel
2. Run the "Add New Domain Alias" wizard in the "Websites & Domains" tab
3. Specify the domain for which you are creating an alias (the primary domain) and the alias’s domain name
4. Make sure the web service is enabled for the domain alias
5. The domain name page should now be shown when you visit the domain alias, but it might take up to 24 hours for changes to take effect.

= Creating a Domain Pointer in DirectAdmin =
1. Login to the DirectAdmin control panel
2. Go to "Domain Pointers" in the "Advanced Features" section
4. Specify the domain for which you are creating a pointer (the primary domain) and the pointer’s domain name
5. The domain name page should now be shown when you visit the domain pointer, but it might take up to 24 hours for changes to take effect.

If you are unable to visit the domain page after you've configured the domain correctly in the control panel of your host, make sure that the name servers for the new domain are the same as the name servers of the primary domain.


== Frequently Asked Questions ==

Have a question? Make a thread in the support forum and we will get back to you.


== Screenshots ==

1.	Domain Name pages overview
2.	Edit domain name page


== Changelog ==

= 1.2.0 =
*	Tweak - WordPress admin menu Domain Names now only visible for users who can manage options.
*	Tweak - WordPress Coding Standards optimizations.

= 1.1.0 =
*	Tweak - Improved support for [Google Analytics by Yoast](https://wordpress.org/plugins/google-analytics-for-wordpress/).

= 1.0.1 =
*	Fixed issue with backwards compatibility PHP and filter_input usage.

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

= 1.0.1 =
Thanks for using the Pronamic Domain Mapping plugin! As always, this update is very strongly recommended.

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
