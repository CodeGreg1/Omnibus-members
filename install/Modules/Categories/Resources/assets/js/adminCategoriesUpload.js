"use strict";

// define app.adminCategoriesUpload object
app.adminCategoriesUpload = {};

// initialize functions of app.adminCategoriesUpload object
app.adminCategoriesUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
    }
};

// initialize app.adminCategoriesUpload object until the document is loaded
$(document).ready(app.adminCategoriesUpload.init());