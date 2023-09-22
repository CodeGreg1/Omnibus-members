"use strict";

app.userManualGatewayDepositCheckout = {};

app.userManualGatewayDepositCheckout.image = function()
{

	var e = $('.checkout-image'), t = $(".dz-preview");
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

                var uploadFiles = app.baseDropzone.countUploaded('.checkout-gateway-image-dropzone-uploaded');

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

app.userManualGatewayDepositCheckout.data = function() {
    var data = new FormData;
    var formData = $('#checkout-form').serializeArray();
    if ($('.checkout-image').length) {
        $.each($('.checkout-image'), function() {
            var image = $(this).get(0).dropzone.getAcceptedFiles();
            var parent = this;
            $.each(image, function (key, file) {
                data.append('user_data['+  $(parent).data('field-name') +']', $(parent)[0].dropzone.getAcceptedFiles()[key]);
            });
        });
    }

    for (let index = 0; index < formData.length; index++) {
        const item = formData[index];
        data.append('user_data['+item.name+']', item.value);
    }

    data.append('amount', parseFloat(app.depositCheckout.getAmount()));

    return data;
};

app.userManualGatewayDepositCheckout.process = function() {
    const self = this;
    $('#btn-submit-manual-deposit-checkout').on('click', function() {
        const route = $(this).data('route');
        var button = $(this);
        const data = self.data();

        if (parseFloat(data.get('amount'))) {
            $.ajax({
                type: 'POST',
                url: route,
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    button.attr('disabled', true).addClass('disabled btn-progress');
                },
                success: function (response, textStatus, xhr) {
                    setTimeout(function() {
                        if (response.message) {
                            app.notify(response.message);
                        }

                        if (response.data.location) {
                            window.location = response.data.location;
                        }
                    }, 500);
                },
                complete: function (xhr) {
                    button.attr('disabled', false).removeClass('disabled btn-progress');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    var response = XMLHttpRequest;
                    button.attr('disabled', false).removeClass('disabled btn-progress');
                    // Check check if form validation errors
                    setTimeout(function() {
                        app.notify(response.responseJSON.message);
                    }, 350);
                    app.formErrors('#checkout-form', response.responseJSON, response.status);
                }
            });
        }
    });
};

app.userManualGatewayDepositCheckout.init = function() {
    if(typeof Dropzone !== 'undefined'
        && $('.checkout-image').length
        && $('.manual-deposit-checkout').length
    ) {
        Dropzone.autoDiscover = false;

		app.userManualGatewayDepositCheckout.image();
    }

    this.process();
};

$(document).ready(app.userManualGatewayDepositCheckout.init());
