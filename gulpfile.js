var elixir = require('laravel-elixir');

var paths = {
    'jquery': './node_modules/jquery/dist/',
    'fontawesome': './node_modules/font-awesome/',
    'css': './resources/css/',
    'js': './resources/js/',
    'images': './resources/images/',
    'fonts': './resources/fonts/',
    'themes': './resources/themes/',
    'storage': './storage/app/',
    'weapons': './resources/images/weapons/'
};

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
 mix
     .copy(paths.jquery + 'cdn/jquery.min.js', 'public/js/jquery.min.js')
     .copy(paths.fontawesome + 'css/font-awesome.min.css', 'public/css/font-awesome.min.css')
     .copy(paths.fontawesome + 'fonts', 'public/fonts/')
     .copy(paths.weapons, 'public/images/weapons/')
     .copy(paths.images + 'bg.png', 'public/css/images/bg.png')
     .copy(paths.images + 'unknown-weapon.png', 'public/images/')
     .copy(paths.storage + 'resources/images/h5-medals.png', 'public/css/images/h5-medals.png')
     .copy(paths.themes, 'public/themes/')
     .copy(paths.css, 'public/css/')
     .copy(paths.js, 'public/js/')
     .styles([
         paths.css + 'main.css',
         paths.css + 'semantic.css',
         paths.storage + 'resources/css/h5-sprites.css'
     ], "public/css/app.css")
     .scripts([
         paths.js + 'main.js',
         paths.js + 'semantic.min.js',
         paths.js + 'tablesort.js'
     ], "public/js/app.js")
     .version(["public/css/app.css", "public/js/app.js"]);
});
