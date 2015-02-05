var elixir = require('laravel-elixir');

var paths = {
    'jquery': './node_modules/jquery/dist/',
    'fontawesome': './node_modules/font-awesome/',
    'semantic': './node_modules/semantic-ui/dist/',
    'css': './resources/css/',
    'js': './resources/js/',
    'images': './resources/images/',
    'fonts': './resources/fonts/'
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
     .copy(paths.images + 'bg.png', 'public/css/images/bg.png')
     .copy(paths.semantic + 'themes', 'public/themes/')
     .copy(paths.css, 'public/css/')
     .copy(paths.js, 'public/js/')
     .styles([
         paths.css + 'main.css',
         paths.css + 'semantic.css'
     ], "public/css/app.css", "./")
     .scripts([
         paths.js + 'main.js',
         paths.js + 'semantic.min.js'
     ], "public/js/app.js", "./")
     .version([
         'css/*',
         'js/*'
     ], "public");
});
