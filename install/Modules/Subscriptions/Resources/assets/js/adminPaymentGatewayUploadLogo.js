app.adminPaymentGatewayUploadLogo = {};

app.adminPaymentGatewayUploadLogo.primaryLogo = function()
{

	var e = $('#settings-paypal_logo-dropzone'), t = $(".dz-preview");
    e.length && (Dropzone.autoDiscover = !1, e.each(function () {
        var e, a, n, o, i;
        e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
        	url:'/',
        	paramName: 'paypal_logo',
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

                var uploadFiles = app.baseDropzone.countUploaded('.settings-paypal_logo-dropzone-uploaded');

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

app.adminPaymentGatewayUploadLogo.init = function() {
    if(typeof Dropzone !== 'undefined') {
        Dropzone.autoDiscover = false;

		app.adminPaymentGatewayUploadLogo.primaryLogo();
    }
};

$(document).ready(app.adminPaymentGatewayUploadLogo.init());
