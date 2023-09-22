"use strict";

app.userAutomaticGatewayDepositCheckout = {};

app.userAutomaticGatewayDepositCheckout.process = function() {
    const self = this;
    $('#btn-submit-automatic-deposit-checkout').on('click', function() {
        const route = $(this).data('route');
        const amount = parseFloat(app.depositCheckout.getAmount());
        const currency = app.depositCheckout.getCurrency();
        var button = $(this);

        if (amount) {
            $.ajax({
                type: 'POST',
                url: route,
                data: {amount, currency},
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
                    button.attr('disabled', false).removeClass('disabled btn-progress');
                    var response = XMLHttpRequest;
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

app.userAutomaticGatewayDepositCheckout.init = function() {
    this.process();
};

$(document).ready(app.userAutomaticGatewayDepositCheckout.init());
