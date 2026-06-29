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
    .sass('resources/sass/app.scss', 'public/css')

    // Tentativa anterior (desativada — mantida comentada para auditoria histórica).
    // .sass('resources/sass/modern-theme.scss', 'public/css/modern-theme.css')

    // SISLAC Design System — porta direta de appsislac (React/Tailwind) para Blade.
    // Compila para: public/css/sislac.css
    .sass('resources/sass/sislac.scss', 'public/css/sislac.css')
    .options({
        processCssUrls: false,
    });
