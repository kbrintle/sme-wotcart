import gulpLoadPlugins from 'gulp-load-plugins';

export const pkg = gulpLoadPlugins( {
	pattern: [ '*', '!gulp', '!gulp-load-plugins' ],
	rename: {
		'browser-sync': 'browserSync',
		'fs-extra-plus': 'fs',
		'gulp-clean-css': 'cleanCss',
		'merge-stream': 'mergeStream',
		'css-mqpacker': 'mqpacker',
		'webpack-stream': 'webpack',
	},
} );


export const serverrc = {
	proxy: 'https://sme.test', // put your local website link here
};

// Source, destination and destination name for compiling and concatenation
export const paths = {
	scss: {
		admin: {
			src: './../backend/web/_assets/src/scss/admin.scss',
			name: 'theme.css',
			dest: './../backend/web/_assets/src/css',
		},
		frontend: {
			src: './../frontend/web/themes/default/_assets/src/scss/stylesheet.scss',
			name: 'compiledScss.css',
			dest: './../frontend/web/themes/default/_assets/src/css',
		},
		watch: {
			admin: './../backend/web/_assets/src/scss/**/*.scss',
			frontend: './../frontend/web/themes/default/_assets/src/scss/**/*.scss',
		},
	},
	css: {
		concat: {
			src: './../frontend/web/themes/default/_assets/src/css/*.css',
			name: `theme.min.css`,
			dest: './../frontend/web/themes/default/_assets/dist/',
		},
	},
	js: {
		concat: {
			admin: {
				src: [
					'./../backend/web/_assets/src/js/jquery-ui.min.js',
					'./../backend/web/_assets/src/js/main.js',
				],
				name: 'sme.js',
				dest: './../backend/web/_assets/dist',
			},
			frontend: {
				src: [ './../frontend/web/themes/default/_assets/src/js/*.js' ],
				name: 'sme.js',
				dest: './../frontend/web/themes/default/_assets/dist',
			},
		},
	},
	php: {
		admin: './../backend/views/**/*.php',
		frontend: './../frontend/themes/default/views/**/*.php',
	},
};
