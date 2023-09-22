"use strict";

// define app.adminManualGatewayUpload object
app.adminManualGatewayUpload = {};
// handle on app.adminWearablesUpload object to initialize dropzone plugin for image field
app.adminManualGatewayUpload.image = function()
{

	var e = $('#admin-manual-gateway-image-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
        	url:'/',
        	paramName: 'image',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            init: function () {
                var myDropzone = this;

                var uploadFiles = app.baseDropzone.countUploaded('.manual-gateway-image-dropzone-uploaded');

                this.options.maxFiles = this.options.maxFiles - uploadFiles;

                this.on("addedfile", function (e) {

                    !a && o && this.removeFile(o), o = e;

                    e.previewElement.querySelector("img").src = app.baseDropzone.config.defaultThumbnail;

                	app.baseDropzone.removeErrors(e);
                });

                this.on("error", function (e, m) {
                	app.notify(m);
                    this.removeFile(e);
                });

            }
        }, n.html(""), e.dropzone(i)
    }));
};

// initialize functions of app.adminManualGatewayUpload object
app.adminManualGatewayUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;

		app.adminManualGatewayUpload.image();
    }
};

// initialize app.adminManualGatewayUpload object until the document is loaded
$(document).ready(app.adminManualGatewayUpload.init());
