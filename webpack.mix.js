const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
.copyDirectory('resources/dist', 'public/dist')
.copyDirectory('resources/webfonts', 'public/webfonts')
.copyDirectory('resources/plugins', 'public/plugins')
/*.copyDirectory('resources/plugins', 'public/plugins')*/

.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .styles([
        'resources/plugins/fontawesome-free/css/all.min.css',
        'resources/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
        'resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'resources/plugins/jqvmap/jqvmap.min.css',
        'resources/dist/css/adminlte.min.css',
        'resources/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
        'resources/plugins/daterangepicker/daterangepicker.css',
        'resources/plugins/summernote/summernote-bs4.min.css',
        'resources/css/app.css',
        ], 'public/css/all.css')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
