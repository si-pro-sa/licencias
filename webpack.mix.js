const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .options({
    extractVueStyles: true,
  })
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/styles.scss', 'public/css') // Ensure Vuetify styles are imported here if needed
  .sass('resources/sass/app.scss', 'public/css') // OR Ensure Vuetify styles are imported here if needed
  .sourceMaps(false, 'source-map')
  .webpackConfig({
    plugins: [],
    resolve: {
      alias: {
        '@styles': path.resolve('resources/sass'), // Changed alias to '@styles' to avoid conflict
        '#': __dirname + '/resources/js',
        '#Components': __dirname + '/resources/js/components',
        '@': __dirname + '/src',  // This was the conflicting alias
      },
    },
  });
