"use strict";

// define app.adminSettingsUpload object
app.adminSettingsUpload = {};

// handle on uploading default profile photo
app.adminSettingsUpload.defaultProfilePhoto = function() 
{
    
    var e = $('#settings-default_profile_photo-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
            url:'/',
            paramName: 'default_profile_photo',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: 'image/*',
            init: function () {
                var myDropzone = this;

                var uploadFiles = app.baseDropzone.countUploaded('.settings-default_profile_photo-dropzone-uploaded');

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

// handle on uploading colored logo
app.adminSettingsUpload.coloredLogo = function() 
{
	
	var e = $('#settings-colored_logo-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
        	url:'/',
        	paramName: 'colored_logo',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: 'image/*',
            init: function () {
                var myDropzone = this;

                var uploadFiles = app.baseDropzone.countUploaded('.settings-colored_logo-dropzone-uploaded');

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

// handle on uploading white logo
app.adminSettingsUpload.whiteLogo = function() 
{
    
    var e = $('#settings-white_logo-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
            url:'/',
            paramName: 'white_logo',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: 'image/*',
            init: function () {
                var myDropzone = this;

                var uploadFiles = app.baseDropzone.countUploaded('.settings-white_logo-dropzone-uploaded');

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

// handle on uploading favicon
app.adminSettingsUpload.favicon = function() 
{
	
	var e = $('#settings-favicon-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
        	url:'/',
        	paramName: 'favicon',
            thumbnailWidth: null,
            thumbnailHeight: null,
            previewsContainer: n.get(0),
            previewTemplate: n.html(),
            maxFiles: 1,
            parallelUploads: 1,
            autoProcessQueue: false,
            uploadMultiple: false,
            acceptedFiles: 'image/*',
            init: function () {
                var myDropzone = this;

                var uploadFiles = app.baseDropzone.countUploaded('.settings-favicon-dropzone-uploaded');

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

// initialize functions of app.adminSettingsUpload object
app.adminSettingsUpload.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;
            
        app.adminSettingsUpload.defaultProfilePhoto();
		app.adminSettingsUpload.coloredLogo();
        app.adminSettingsUpload.whiteLogo();
		app.adminSettingsUpload.favicon();
    }
};

// initialize app.adminSettingsUpload object until the document is loaded
$(document).ready(app.adminSettingsUpload.init());