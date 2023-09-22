"use strict";

app.userSubscriptionsManualPaymentCheckout = {};

app.userSubscriptionsManualPaymentCheckout.config = {
    form: '#subscription-manual-payment-checkout'
};

// handle app.adminManualGatewayCreate object ajax request
app.userSubscriptionsManualPaymentCheckout.ajax = function() {
    const $self = this;
    $(app.userSubscriptionsManualPaymentCheckout.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.userSubscriptionsManualPaymentCheckout.config.form).find(':submit');
        var $content = $button.html();
        console.log($button.data('route'))
        $.ajax({
            type: 'POST',
            url: $button.data('route'),
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                setTimeout(function() {
                    if (response.message) {
                        app.notify(response.message);
                    }

                    if (response.data.redirectTo) {
                        app.redirect(response.data.redirectTo);
                    }
                }, 500);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.userSubscriptionsManualPaymentCheckout.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

// initialize functions of app.userSubscriptionsManualPaymentCheckout object
app.userSubscriptionsManualPaymentCheckout.init = function() {
    app.userSubscriptionsManualPaymentCheckout.ajax();
};

// initialize app.userSubscriptionsManualPaymentCheckout object until the document is loaded
$(document).ready(app.userSubscriptionsManualPaymentCheckout.init());
