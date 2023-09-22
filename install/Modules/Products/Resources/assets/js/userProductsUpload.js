app.userProductsUpload = {};

app.userProductsUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
    }
};

$(document).ready(app.userProductsUpload.init());