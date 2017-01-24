const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copy('semantic/dist/semantic.min.css', 'public/css/semantic.min.css')
    .copy('semantic/dist/semantic.min.js', 'public/js/semantic.min.js');
