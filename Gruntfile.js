module.exports = function( grunt ) {
	// Project configuration.
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		// PHPLint
		phplint: {
			options: {
				phpArgs: {
					'-lf': null
				}
			},
			all: [ 'classes/**/*.php' ]
		},

		// PHP Code Sniffer
		phpcs: {
			application: {
				src: [ '**/*.php' ],
			},
			options: {
				standard: 'phpcs.ruleset.xml',
				extensions: 'php',
				ignore: 'wp-svn,deploy,node_modules'
			}
		},

		// JSHint
		jshint: {
			files: ['Gruntfile.js' ],
			options: {
				// options here to override JSHint defaults
				globals: {
					jQuery: true,
					console: true,
					module: true,
					document: true
				}
			}
		},

		// Check WordPress version
		checkwpversion: {
			options: {
				readme: 'readme.txt',
				plugin: 'pronamic-domain-mapping.php',
			},
			check: {
				version1: 'plugin',
				version2: 'readme',
				compare: '=='
			},
			check2: {
				version1: 'plugin',
				version2: '<%= pkg.version %>',
				compare: '=='
			}
		},

		// Make POT
		makepot: {
			target: {
				options: {
					cwd: '',
					domainPath: 'languages',
					type: 'wp-plugin',
					exclude: [ 'deploy/.*', 'wp-svn/.*' ],
				}
			}
		},

		// Copy
		copy: {
			deploy: {
				src: [
					'**',
					'!.*',
					'!.*/**',
					'!Gruntfile.js',
					'!package.json',
					'!phpcs.ruleset.xml',
					'!node_modules/**',
					'!wp-svn/**'
				],
				dest: 'deploy',
				expand: true,
				dot: true
			},
		},

		// Clean
		clean: {
			deploy: {
				src: [ 'deploy' ]
			},
		},

		// WordPress deploy
		rt_wp_deploy: {
			app: {
				options: {
					svnUrl: 'http://plugins.svn.wordpress.org/pronamic-domain-mapping/',
					svnDir: 'wp-svn',
					svnUsername: 'pronamic',
					deployDir: 'deploy',
					version: '<%= pkg.version %>',
				}
			}
		},
	} );

	grunt.loadNpmTasks( 'grunt-phplint' );
	grunt.loadNpmTasks( 'grunt-phpcs' );
	grunt.loadNpmTasks( 'grunt-contrib-clean' );
	grunt.loadNpmTasks( 'grunt-contrib-copy' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-checkwpversion' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-rt-wp-deploy' );

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpcs', 'checkwpversion' ] );
	grunt.registerTask( 'pot', [ 'makepot' ] );

	grunt.registerTask( 'deploy', [
		'checkwpversion',
		'clean:deploy',
		'copy:deploy'
	] );

	grunt.registerTask( 'wp-deploy', [
		'deploy',
		'rt_wp_deploy'
	] );
};
