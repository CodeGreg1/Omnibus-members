const mix = require('laravel-mix');
const fs = require('fs');
const path = require('path');
require('laravel-mix-merge-manifest');

mix.webpackConfig({
  stats: {
    children: true,
  },
});

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

const themeFiles = [
  'theme.css',
  'theme.min.css',
  'theme.js',
  'theme.min.js'
];

const moduleFolder = './Modules';
const themesPath = '/Modules/Base/Themes';

const dirs = p => fs.readdirSync(p).filter(f => fs.statSync(path.resolve(p,f)).isDirectory());

let modules = dirs(moduleFolder);
let authors = dirs(__dirname + themesPath);

authors.forEach(function(author) {
  var authorFolderPath = themesPath + '/' + author;
  var themesFolderPath = __dirname + authorFolderPath;
  let themes = dirs(themesFolderPath);

  // Loop themes
  themes.forEach(function(theme) {
    var themePublicPath = themesFolderPath + '/' + theme + '/public';
    var jsPath = themePublicPath + "/js";
    var cssPath = themePublicPath + "/css";
    var sassPath = themePublicPath + "/sass";
    var jsFolder = authorFolderPath + '/' + theme + '/public/js';
    var cssFolder = authorFolderPath + '/' + theme + '/public/css';
    var sassFolder = authorFolderPath + '/' + theme + '/public/sass';

    var publicThemeSassFolder = __dirname + '/public' + authorFolderPath + '/' + theme + '/public/sass';

    var themeCompiledJs = [];
    var themeCompiledCss = [];
    var themeCompiledSass = [];

    if(fs.existsSync(jsPath)) {
      var jsFiles = fs.readdirSync(jsPath);

      // Loop theme js files
      for(var i = 0; i < jsFiles.length; i++) {
        if(!themeFiles.includes(jsFiles[i]) && jsFiles[i] != '_main.js') {
          themeCompiledJs.push(jsPath + '/' + jsFiles[i]);
        }
      }

      themeCompiledJs.push(jsPath + '/' + '_main.js');

      mix.combine(themeCompiledJs, jsPath + '/theme.min.js', jsPath);
      mix.copy(jsPath + '/theme.min.js', 'public' + jsFolder)
    }
    
    if(fs.existsSync(sassPath)) {
      var sassFiles = fs.readdirSync(sassPath);

      // Loop theme css files
      for(var i = 0; i < sassFiles.length; i++) {
        var sassFileName = sassFiles[i];

        if(!themeFiles.includes(sassFileName)) {
          mix.copy(__dirname + sassFolder + '/' + sassFileName, publicThemeSassFolder);
          
          if(!fs.existsSync(publicThemeSassFolder + '/' + sassFileName) && sassFileName != 'main.scss') {
            
            var removedUnderscoreSassFileName = sassFileName.replace("_", "");
            fs.writeFile(publicThemeSassFolder + '/' + removedUnderscoreSassFileName.replace(".scss", ".css"), '', function (err) {});
            fs.writeFile(publicThemeSassFolder + '/' + sassFileName.replace(".scss", ".css"), '', function (err) {});
          }
        }
      }

      if(fs.existsSync(publicThemeSassFolder + '/main.scss')) {
        mix.sass(publicThemeSassFolder + '/main.scss', sassPath);
      }
    }


    if(fs.existsSync(cssPath)) {
      var cssFiles = fs.readdirSync(cssPath);

      // Loop theme css files
      for(var i = 0; i < cssFiles.length; i++) {
        if(!themeFiles.includes(cssFiles[i])) {
          themeCompiledCss.push(cssPath + '/' + cssFiles[i]);
        }
      }

      if(fs.existsSync(publicThemeSassFolder + '/main.css')) {
        themeCompiledCss.push(publicThemeSassFolder + '/main.css');
      }
      
      mix.combine(themeCompiledCss, cssPath + '/theme.min.css', cssPath);
      mix.copy(cssPath + '/theme.min.css', 'public' + cssFolder)
    }
  });
});


// Loop available modules
modules.forEach(function(modul){
  var lowerModulName = modul.toLowerCase();
  var jsFilesPath = __dirname  + "/Modules/" + modul + "/Resources/assets/site/js";
  var cssFilesPath = __dirname  + "/Modules/" + modul + "/Resources/assets/site/css";
    
    // For site javascripts
    if(fs.existsSync(jsFilesPath)) {
      var siteJsPlugins = [];
      var siteJsScripts = [];

      var jsFiles = fs.readdirSync(jsFilesPath);

      // Loop module js files
      for(var i = 0; i < jsFiles.length; i++) {
        // Check if the file is plugins.json
        if(jsFiles[i] == 'plugins.json') {
          var plugins = JSON.parse(fs.readFileSync(jsFilesPath + '/plugins.json', 'utf8'));

          for (var key in plugins) {
            if (plugins.hasOwnProperty(key)) {
              siteJsPlugins.push(plugins[key].plugin);
            }
          }
        } else {
          siteJsScripts.push(jsFilesPath + '/' + jsFiles[i]);
        }
      }//end loop

      mix.combine(siteJsPlugins.concat(siteJsScripts), 'public/js/site/' + lowerModulName + '.min.js', 'public/js/site');
    }


    if(fs.existsSync(cssFilesPath)) {
      var siteCssPlugins = [];
      var siteCssScripts = [];

      var cssFiles = fs.readdirSync(cssFilesPath);

      // Loop module js files
      for(var i = 0; i < cssFiles.length; i++) {
        // Check if the file is plugins.json
        if(cssFiles[i] == 'plugins.json') {
          var plugins = JSON.parse(fs.readFileSync(cssFilesPath + '/plugins.json', 'utf8'));

          for (var key in plugins) {
            if (plugins.hasOwnProperty(key)) {
              siteCssPlugins.push(plugins[key].plugin);
            }
          }
        } else {
          siteCssScripts.push(cssFilesPath + '/' + cssFiles[i]);
        }
      }//end loop

      mix.combine(siteCssPlugins.concat(siteCssScripts), 'public/css/site/' + lowerModulName + '.min.css', 'public/css/site');
    }
    
});

var baseJavascripts = [
  'public/plugins/jquery/js/jquery-3.6.4.min.js',
  'public/plugins/bootstrap5/js/bootstrap.min.js'
];

var baseStylesheets = [
  'public/plugins/bootstrap5/css/bootstrap.min.css'
];

mix.combine(baseJavascripts, 'public/js/site/base.min.js', 'public/js/site');
mix.combine(baseStylesheets, 'public/css/site/base.min.css', 'public/css/site');
mix.mergeManifest();
