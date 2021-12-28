const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |  Potrebno je dodati nastavak .vue({ version: 2 }), ako slučajno npm run watch se ne pokreće !!!
 */
mix.js('resources/js/app.js', 'public/js').vue({ version: 2 })
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
