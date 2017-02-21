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

elixir(function(mix) {
    mix.sass('app.scss').livereload();
    mix.browserify('index.js').livereload();
});

// elixir(function(mix2) {
	// mix2.sass('lander.scss').livereload();
	// mix2.browserify('lander.js').livereload();
// });

// elixir(function(mix3) {
	// mix3.version(['public/js/lander.js','public/css/lander.css', 'public/js/index.js','public/css/app.css']).livereload();
// });
