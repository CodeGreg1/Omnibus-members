"use strict";

// define app.adminPageImageUpload object
app.adminPageImageUpload = {};

// handle on app.adminPageImageUpload object to initialize dropzone plugin for attachments field
app.adminPageImageUpload.sectionBackgroundImage = function()
{
    var e = $('#page-background_image-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
            url:'/',
            paramName: 'background_image',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: '.jpeg,.jpg,.png',
            init: function () {
                var uploadFiles = app.baseDropzone.countUploaded('.tickets-attachments-dropzone-uploaded');

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

// initialize functions of app.adminPageImageUpload object
app.adminPageImageUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;

        app.adminPageImageUpload.sectionBackgroundImage();
    }
};

// initialize app.adminPageImageUpload object until the document is loaded
$(document).ready(app.adminPageImageUpload.init());
