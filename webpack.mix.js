const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/tinymce.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/tinymce/skins', 'public/css/tinymce/skins')
    .sass('vendor/forkawesome/fork-awesome/scss/fork-awesome.scss', 'public/css')
    .js('resources/js/benutzerfreundlichkeit.js', 'public/js')
    .scripts([
        'resources/js/benutzerfreundlichkeit.js',
        'resources/js/generatePlaceholders.js',
        'resources/js/popover.js',
    ], 'public/js/benutzerfreundlichkeit.js');


mix.copy('node_modules/tinymce/skins', 'public/js/skins');
