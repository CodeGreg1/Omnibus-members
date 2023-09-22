"use strict";

// define app.adminTicketsUpload object
app.adminTicketsUpload = {};

// handle on app.adminTicketsUpload object to initialize dropzone plugin for attachments field
app.adminTicketsUpload.attachments = function() 
{
	
	var e = $('#ticket-reply-attachments-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
        	url:'/',
        	paramName: 'attachments',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 10,
            parallelUploads: 10,
            autoProcessQueue: false,
            uploadMultiple: true,
            init: function () {
                var myDropzone = this;

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

// initialize functions of app.adminTicketsUpload object
app.adminTicketsUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
        
		app.adminTicketsUpload.attachments();
    }
};

// initialize app.adminTicketsUpload object until the document is loaded
$(document).ready(app.adminTicketsUpload.init());