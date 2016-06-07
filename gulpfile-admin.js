var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');
require('laravel-elixir-livereload');

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

elixir.config.js.browserify.watchify.options.poll = true;
elixir.config.assetsDir = 'resources/assets/admin/';

elixir(function(mix) {
    mix.sass('./resources/assets/admin/sass/app.scss', 'public/css/admin/app.css').version('public/css/admin/app.css').livereload();
    mix.browserify('index.js', 'public/js/admin/index.js').version('public/js/admin/index.js').livereload();
});