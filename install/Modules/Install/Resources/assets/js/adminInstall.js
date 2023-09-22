"use strict";

// define app.adminInstall object
app.adminInstall = {};

// call all available functions of app.adminInstall object to initialize
app.adminInstall.init = function() {
    $( "#start-installation" ).click(function() {
    	app.buttonLoader($(this));
        $( "#form-installation" ).submit();
    });
};

// initialize app.adminInstall object until the document is loaded
$(document).ready(app.adminInstall.init());