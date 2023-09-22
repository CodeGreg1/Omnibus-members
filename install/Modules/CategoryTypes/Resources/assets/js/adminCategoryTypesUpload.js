"use strict";

// define app.adminCategoryTypesUpload object
app.adminCategoryTypesUpload = {};

// initialize functions of app.adminCategoryTypesUpload object
app.adminCategoryTypesUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
    }
};

// initialize app.adminCategoryTypesUpload object until the document is loaded
$(document).ready(app.adminCategoryTypesUpload.init());