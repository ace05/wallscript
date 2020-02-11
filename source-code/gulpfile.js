var elixir = require('laravel-elixir');

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

var path = {
    "jquery": "../bower/jquery/dist/",
    "jqueryMouseWheel": "../bower/jquery-mousewheel/",
	"scrollBar": "../bower/malihu-custom-scrollbar-plugin/",
    "fontawesome": "../bower/components-font-awesome/",
    "bootstrapSelect": "../bower/bootstrap-select/",
    "bootstrapAlert": "../bower/sweetalert2/",
    "select2" : "../bower/select2/",
    "select2Bootstrap" : "../bower/select2-bootstrap-theme/",
    'typeahead': "../bower/typeahead.js/dist/"
};

elixir(function(mix) {
    mix.styles([
        "bootstrap/css/bootstrap.css",
        path.fontawesome + "/css/font-awesome.css",
        path.bootstrapSelect + "/dist/css/bootstrap-select.css",
    	path.bootstrapAlert + "/dist/sweetalert2.css",
        path.select2Bootstrap + "/dist/select2-bootstrap.css",
        path.select2 + "/dist/css/select2.css",
        path.scrollBar + "/jquery.mCustomScrollbar.css",
        'style.css',
        'jquery-ui.css',
        'jquery-ui.structure.css',
        'jquery-ui.theme.css',
        'emotions.css',
        'typeahead.css',
        'lightbox.css',
    ], 'public/css/app.css');
    
    mix.styles([
        "bootstrap/css/bootstrap.css",
        path.fontawesome + "/css/font-awesome.css",
        path.scrollBar + "/jquery.mCustomScrollbar.css",
        'admin/admin.css',
    ], 'public/css/admin.css');

    mix.scripts([
        path.jquery + 'jquery.js',
        'bootstrap.js',
        'jquery-ui.js',
        path.bootstrapSelect + "/dist/js/bootstrap-select.js",
        "jquery.geocomplete.js",
        "jquery.form.js",
        "lightbox.js",
        path.bootstrapAlert + "/dist/sweetalert2.js",
        path.select2 + "/dist/js/select2.full.js",
        path.typeahead + 'typeahead.bundle.js',
        'expanding.js',
        'script.js'
    ], 'public/js/app.js'); 

    mix.scripts([
        path.jquery + 'jquery.js',
       'bootstrap.js',
        path.jqueryMouseWheel + 'jquery.mousewheel.js',
        path.scrollBar + 'jquery.mCustomScrollbar.js',
        'admin/theme.js'
    ], 'public/js/admin.js');   

    mix.version(['css/app.css', 'css/admin.css', 'js/app.js', 'js/admin.js']);
    mix.copy('resources/assets/css/bootstrap/fonts/', 'public/build/fonts/');
    mix.copy('resources/assets/bower/components-font-awesome/fonts/', 'public/build/fonts/');
    mix.copy('resources/assets/img/', 'public/build/img/');
});
