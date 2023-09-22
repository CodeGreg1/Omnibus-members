app.adminProductsUpload = {};

app.adminProductsUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
    }
};

$(document).ready(app.adminProductsUpload.init());