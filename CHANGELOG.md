# Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased][unreleased]

<!-- Start changelog -->

## [2.0.4] - 2026-08-18

### Fixed

- Replaced deprecated string sanitization filters with WordPress text sanitization.
- Avoided processing the domain mapping meta box when its nonce is missing.

### Dependencies

- Updated `dealerdirect/phpcodesniffer-composer-installer` to `v1.2.1`, fixing installation with `open_basedir` restrictions and improving CI housekeeping ([release notes](https://github.com/PHPCSStandards/composer-installer/releases/tag/v1.2.1)).
- Updated `guzzlehttp/guzzle` to `7.15.3`, fixing cURL multi-handler tracking and numeric IPv4 handling in stream and TLS handlers ([release notes](https://github.com/guzzle/guzzle/releases/tag/7.15.3)).
- Updated `guzzlehttp/promises` to `2.5.2`, fixing `EachPromise` aggregate settlement and preventing new work after settlement ([release notes](https://github.com/guzzle/promises/releases/tag/2.5.2)).
- Updated `guzzlehttp/psr7` to `2.13.0`, adding ASCII case utilities and locale-independent case folding ([release notes](https://github.com/guzzle/psr7/releases/tag/2.13.0)).
- Updated `phpcsstandards/phpcsextra` to `1.5.1`, raising the PHPCSUtils requirement and performing housekeeping ([release notes](https://github.com/PHPCSStandards/PHPCSExtra/releases/tag/1.5.1)).
- Updated `phpcsstandards/phpcsutils` to `1.2.3`, fixing array parsing edge cases and an arbitrary command execution vulnerability when scanning untrusted code ([release notes](https://github.com/PHPCSStandards/PHPCSUtils/releases/tag/1.2.3)).
- Updated `squizlabs/php_codesniffer` to `3.13.6`, fixing shell command execution through file names in Gitblame, Hgblame, and Svnblame reports ([release notes](https://github.com/PHPCSStandards/PHP_CodeSniffer/releases/tag/3.13.6)).
- Updated `symfony/deprecation-contracts` to `v3.7.1`; this release contains no significant changes ([release notes](https://github.com/symfony/contracts/releases/tag/v3.7.1)).
- Updated `symfony/polyfill-ctype` to `v1.37.0`, including PHP 8.6 grapheme support and safer polyfill registration ([release notes](https://github.com/symfony/polyfill/releases/tag/v1.37.0)).
- Updated `symfony/polyfill-intl-normalizer` to `v1.38.0`, incorporating the Symfony Polyfill component fixes from that release ([release notes](https://github.com/symfony/polyfill/releases/tag/v1.38.0)).
- Updated `symfony/polyfill-mbstring` to `v1.38.2`, fixing `mb_scrub()` and `mb_str_pad()` behavior ([release notes](https://github.com/symfony/polyfill/releases/tag/v1.38.2)).
- Updated `symfony/polyfill-php80` to `v1.37.0`, incorporating the Symfony Polyfill deep-clone and registration fixes from that release ([release notes](https://github.com/symfony/polyfill/releases/tag/v1.37.0)).
- Updated `symfony/yaml` to `v7.4.13`, allowing trailing newlines after the YAML end-of-document marker ([release notes](https://github.com/symfony/symfony/releases/tag/v7.4.13)).
- Updated `wp-coding-standards/wpcs` to `3.4.1`, fixing a security issue in the `WordPress.WP.EnqueuedResourceParameters` sniff and updating PHPCS utility requirements ([release notes](https://github.com/WordPress/WordPress-Coding-Standards/releases/tag/3.4.1)).

[2.0.4]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/2.0.3...v2.0.4

## [2.0.3] - 2026-02-19

### Fixed

- Fixed cache collision for SiteGround Optimizer.

[2.0.3]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/2.0.2...v2.0.3

## [2.0.2] - 2022-06-07

### Fixed

- Fixed excluding post types from Yoast SEO sitemap.

[2.0.2]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/2.0.1...2.0.2

## [2.0.1] - 2021-06-28

### Fixed

- Fixed creating required database table.

[2.0.1]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/2.0.0...2.0.1

## [2.0.0] - 2021-06-25

### Added

- Added support for block editor on domain name pages.

### Changed

- Supports adding `pronamic_domain_mapping` post type support.

[2.0.0]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.3.2...2.0.0

## [1.3.2] - 2017-10-16

### Added

- Added `page-attributes` support to the `pronamic_domain_page` custom post type.

[1.3.2]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.3.1...1.3.2

## [1.3.1] - 2016-09-14

### Added

- Added installation instructions for cPanel.

### Changed

- Removed pronamic_domain_page from WordPress SEO sitemaps.

[1.3.1]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.3.0...1.3.1

## [1.3.0] - 2016-02-12

### Changed

- Return default post link for draft posts.
- Added support for redirect canonical if post status is concept or private.
- Added support for https protocol.
- Added support for WordPress MU Domain Mapping.
- Reduced the domain length to 128 to fix max key length.
- Simplified adding meta box.
- WordPress Coding Standards optimizations.

[1.3.0]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.2.0...1.3.0

## [1.2.0] - 2015-06-09

### Changed

- WordPress admin menu Domain Names now only visible for users who can manage options.
- WordPress Coding Standards optimizations.

[1.2.0]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.1.0...1.2.0

## [1.1.0] - 2014-11-04

### Changed

- Improved support for [Google Analytics by Yoast](https://wordpress.org/plugins/google-analytics-for-wordpress/).

[1.1.0]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.0.1...1.1.0

## [1.0.1] - 2013-10-01

### Fixed

- Fixed issue with backwards compatibility PHP and filter_input usage.

[1.0.1]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/1.0.0...1.0.1

## [1.0.0] - 2013-10-01

### Added

- Added support for WordPress network/multisite installations.
- Added support for the [WordPress SEO by Yoast](https://wordpress.org/plugins/wordpress-seo/) plugin.
- Added support for the [Google Analytics for WordPress](https://wordpress.org/plugins/google-analytics-for-wordpress/) plugin.

[1.0.0]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/0.1.3...1.0.0

## [0.1.3] - 2013-04-29

### Added

- Added support for The WordPress Multilingual Plugin (https://wpml.org/).

[0.1.3]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/0.1.2...0.1.3

## [0.1.2] - 2013-03-25

### Added

- Added some screenshots.

[0.1.2]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/0.1.1...0.1.2

## [0.1.1] - 2013-03-25

### Fixed

- Fixed notice missing argument.

[0.1.1]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/0.1...0.1.1

## [0.1] - 2013-03-12

### Added

- Initial release.

[0.1]: https://github.com/pronamic/wp-pronamic-domain-mapping/releases/tag/0.1

<!-- End changelog -->

[unreleased]: https://github.com/pronamic/wp-pronamic-domain-mapping/compare/v2.0.3...HEAD
