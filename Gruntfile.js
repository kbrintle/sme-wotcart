const grunt = require('grunt')
const sass = require('node-sass')

require('load-grunt-tasks')(grunt)

module.exports = function(grunt) {

    // task configurations
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /**
         * Sass
         */
        sass: {
            options: {
                implementation: sass,
                sourceMap: false
            },
            dist: {
                options: {
                    sourceMap: true
                },
                files: {
                    'backend/web/_assets/src/css/admin.css': 'backend/web/_assets/src/scss/admin.scss',
                    'frontend/web/themes/default/_assets/src/css/site.css': 'frontend/web/themes/default/_assets/src/scss/stylesheet.scss'
                }
            }
        },

        /**
         * Copy
         */
        copy: {
            backend: {
                expand: false,
                src: 'backend/web/_assets/src/css/admin.css',
                dest: 'backend/web/_assets/dist/theme.css'
            }
        },

        /**
         * JS Concat
         */
        concat: {
            frontendCss: {
                src: 'frontend/web/themes/default/_assets/src/css/*.css',
                dest: 'frontend/web/themes/default/_assets/dist/theme.css'
            },

            backend_factories: {
                /*   src: ["backend/web/_assets/src/js/angular/factories/!**.js"],
                   dest: "backend/web/_assets/src/js/angular/dist/factories.js"*/
            },
            backend_filters: {
                /*   src: ["backend/web/_assets/src/js/angular/filters/!**.js"],
                   dest: "backend/web/_assets/src/js/angular/dist/filters.js"*/
            },
            backend_controllers: {
                // src: ["backend/web/_assets/src/js/angular/controllers/**.js"],
                // dest: "backend/web/_assets/src/js/angular/dist/controllers.js"
            },
            backend: {
                src: [
                    'backend/web/_assets/src/js/jquery-ui.min.js',
                    'backend/web/_assets/src/js/main.js'
                    /* "backend/web/_assets/src/js/angular.min.js",
                    "backend/web/_assets/src/js/app.js",
                    "backend/web/_assets/src/js/angular/dist/factories.js",
                    "backend/web/_assets/src/js/angular/dist/filters.js",
                    "backend/web/_assets/src/js/angular/dist/controllers.js"*/
                ],
                dest: 'backend/web/_assets/dist/sme.js'
            },
            frontend: {
                src: ['frontend/web/themes/default/_assets/src/js/*.js'],
                dest: 'frontend/web/themes/default/_assets/dist/sme.js'
            }
        },

        /**
         * CSS Minify
         */
        cssmin: {
            options: {
                mergeIntoShorthands: true,
                roundingPrecision: -1
            },
            target: {
                files: {
                    'frontend/web/themes/default/_assets/dist/theme.css': ['frontend/web/themes/default/_assets/dist/theme.css'],
                    'backend/web/_assets/dist/theme.css': ['backend/web/_assets/dist/theme.css']
                }
            }
        },

        /**
         * JS Minify
         */
        terser: {
            options: {},
            files: {
                'backend/web/_assets/dist/sme.js': ['backend/web/_assets/dist/sme.js'],
                'frontend/web/themes/default/_assets/dist/sme.js': ['frontend/web/themes/default/_assets/dist/sme.js']
            }
        },


        /**
         * Watch
         */
        watch: {
            sass: {
                files: ['backend/web/_assets/src/scss/**', 'frontend/web/themes/default/_assets/src/scss/**'],
                tasks: ['sass', 'copy']
            },
            js: {
                files: ['backend/web/_assets/src/js/angular/**', '!backend/web/_assets/src/js/angular/dist/**'],
                tasks: ['concat']
            }
        }

    })


    // npm modules
    grunt.loadNpmTasks('grunt-contrib-concat')
    grunt.loadNpmTasks('grunt-contrib-copy')
    grunt.loadNpmTasks('grunt-contrib-watch')
    grunt.loadNpmTasks('grunt-contrib-cssmin')


    // grunt tasks
    grunt.registerTask('default', ['sass', 'copy', 'concat', 'watch'])// use for dev
    grunt.registerTask('no-watch', ['sass', 'copy', 'concat'])// use for dev for single task runs
    grunt.registerTask('prod', ['sass', 'copy', 'cssmin', 'concat', 'terser'])   // use for prod

}
