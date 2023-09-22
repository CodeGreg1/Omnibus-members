"use strict";

// define app.adminServicesUpload object
app.adminServicesUpload = {};

// initialize functions of app.adminServicesUpload object
app.adminServicesUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
    }
};

// initialize app.adminServicesUpload object until the document is loaded
$(document).ready(app.adminServicesUpload.init());