"use strict";

app.userWithdrawCheckout = {};

app.userWithdrawCheckout.config = {
    section: '#withdraw-checkout-section',
    route: {
        process: '/user/withdrawals/checkout/process',
        store: '/user/withdrawals/checkout/store',
    }
};

app.userWithdrawCheckout.getAmount = function() {
    return parseFloat($(this.config.section+' [name="amount"]').val() || 0);
};

app.userWithdrawCheckout.getCurrency = function() {
    return $(this.config.section+' #method-currency').val();
};

app.userWithdrawCheckout.getFixedCharge = function() {
    return parseFloat($(this.config.section+' #method-fixed-charge').val());
};

app.userWithdrawCheckout.getPercentCharge = function() {
    return parseFloat($(this.config.section+' #method-percent-charge').val());
};

app.userWithdrawCheckout.initTotal = function() {
    const amntCurrency = this.getCurrency();
    const amount = this.getAmount();
    const percentChargeValue = this.getPercentCharge();
    const fixCharge = this.getFixedCharge();
    var percentCharge = 0;
    if (percentChargeValue) {
        percentCharge =(percentChargeValue/100) * amount;
    }
    var total = 0;
    if (amount) {
        total = amount - (fixCharge + percentCharge);
    }

    $('#checkout-withdraw-percent-charge').html(app.currency.format(percentCharge, amntCurrency));
    $('.checkout-withdraw-total').html(app.currency.format(total, amntCurrency));
};

app.userWithdrawCheckout.image = function()
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

app.userWithdrawCheckout.data = function() {
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

    data.append('method', $('#method').val());
    data.append('amount', parseFloat(app.userWithdrawCheckout.getAmount()));

    return data;
};

app.userWithdrawCheckout.process = function() {
    const self = this;
    $('#btn-submit-withdraw-checkout').on('click', function() {
        var button = $(this);
        const data = self.data();

        if (parseFloat(data.get('amount'))) {
            $.ajax({
                type: 'POST',
                url: self.config.route.process,
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    button.attr('disabled', true).addClass('disabled btn-progress');
                },
                success: function (response, textStatus, xhr) {
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

                    if (window.otp_wothdrawal) {
                        app.walletOtp.confirm({
                            success: function(response) {
                                $.ajax({
                                    type: 'POST',
                                    url: self.config.route.store,
                                    data: data,
                                    contentType: false,
                                    cache: false,
                                    processData: false,
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
                            },
                            error: function(response) {
                                button.attr('disabled', false).removeClass('disabled btn-progress');
                                // Check check if form validation errors
                                setTimeout(function() {
                                    app.notify(response.responseJSON.message);
                                }, 350);
                                app.formErrors('#checkout-form', response.responseJSON, response.status);
                            },
                            always: function() {
                                button.attr('disabled', false).removeClass('disabled btn-progress');
                            }
                        }, 'withdraw_checkout');
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: self.config.route.store,
                            data: data,
                            contentType: false,
                            cache: false,
                            processData: false,
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

app.userWithdrawCheckout.init = function() {
    $(this.config.section+' [name="amount"]').on('input', function() {
        app.userWithdrawCheckout.initTotal();
    });

    if(typeof Dropzone !== 'undefined'
        && $('.checkout-image').length
        && $('.manual-withdraw-checkout').length
    ) {
        Dropzone.autoDiscover = false;

		app.userWithdrawCheckout.image();
    }

    this.process();
};

$(document).ready(app.userWithdrawCheckout.init());
