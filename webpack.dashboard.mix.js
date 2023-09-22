const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');
require('laravel-mix-merge-manifest');

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

const moduleFolder = './Modules';

const dirs = p => fs.readdirSync(p).filter(f => fs.statSync(path.resolve(p,f)).isDirectory());

// Get the available modules return as array
let modules = dirs(moduleFolder);

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css');

var modulesCompiledCss = [];
var modulesCompiledJs = [];

// delete temporary public/js/tmp/modules directory
fs.rmSync('./public/js/tmp/modules', { recursive: true, force: true });

// Loop available modules
modules.forEach(function(mod){
    var jsDir = __dirname  + "/Modules/" + mod + "/Resources/assets/js/";
    var cssDir = __dirname  + "/Modules/" + mod + "/Resources/assets/sass/";

    if(fs.existsSync(jsDir)) {
        var jsFiles = fs.readdirSync(jsDir);

        // Loop module js files
        for(var i = 0; i < jsFiles.length; i++) {
            modulesCompiledJs.push("public/js/tmp/" + jsFiles[i]);

            mix.js(__dirname  + "/Modules/" + mod + "/Resources/assets/js/"+jsFiles[i], 'public/js/tmp');
        }
    }

    if(fs.existsSync(cssDir)) {
        var cssFiles = fs.readdirSync(cssDir);

        // Loop module css files
        for(var i = 0; i < cssFiles.length; i++) {
            var cssFileName = cssFiles[i];

            modulesCompiledCss.push("public/css/tmp/" + cssFileName.replace(".scss", ".css"));

            mix.sass(__dirname  + "/Modules/" + mod + "/Resources/assets/sass/"+cssFiles[i], 'public/css/tmp');
        }
    }
});

// Remove duplicate js filename
modulesCompiledJs = modulesCompiledJs.sort().filter(function(item, pos, ary) {
    return !pos || item != ary[pos - 1];
});

// Remove duplicate css filename
modulesCompiledCss = modulesCompiledCss.sort().filter(function(item, pos, ary) {
    return !pos || item != ary[pos - 1];
});

// Let's parse again to fix the newline every module for css & js
mix.styles(modulesCompiledCss, 'public/css/app.min.css', 'public/css');
mix.combine(modulesCompiledJs, 'public/js/app.min.js', 'public/js');

if (mix.inProduction()) {
    mix.version();
}

mix.mergeManifest();