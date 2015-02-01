var elixir = require('laravel-elixir');

var paths = {
 'semantic': './node_modules/semantic-ui/dist/',
 'jquery': './node_modules/jquery/dist/'
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
     .copy(paths.semantic + 'themes/default', 'public/default')
     .copy(paths.jquery + 'jquery.js', 'public/js/jquery.js')
     .styles([
         paths.semantic + 'semantic.css'
     ], "public/css/app.css", "./")
     .scripts([
         paths.semantic + 'semantic.js'
     ], "public/js/app.js", "./")
     .version([
         'js/*',
         'css/*'
     ], "public");
});
