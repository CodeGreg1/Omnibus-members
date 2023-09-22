"use strict";

app.cashierGatewayUpdate = {};

// handle app.adminSettingsCreate object configuration
app.cashierGatewayUpdate.config = {
    gatewayTab: '#gateway-tablist',
    settingstab: 'gateway-settings-tab',
    redirect: '/admin/settings/payment-gateways',
    button: '#btn-save-cashier-gateway-settings'
};

// handle app.adminSettingsCreate object ajax request
app.cashierGatewayUpdate.ajax = function() {
    var $self = this;
    $(app.cashierGatewayUpdate.config.button).on('click', function(e) {

        e.preventDefault();

        var $button = $(this);
        const $content = $button.html();
        const gateway = $($self.config.gatewayTab + ' .nav-link.active').data('value');
        const form = $('#'+gateway+'_' +$self.config.settingstab + ' .nav-link.active').data('form');
        var data = new FormData($(form)[0]);
        var images = $(`[data-image-gallery="${gateway}"]`)
                                        .sGallery()
                                        .images();
        if (images.length) {
            data.append(gateway+'_logo', images[0].original_url);
        }
        data.append('gateway', gateway);

        $.ajax({
            type: 'POST',
            url: $(form).data('route'),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);

                setTimeout(function() {
                    app.redirect(app.cashierGatewayUpdate.config.redirect);
                }, 1000);

                app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });

};

// initialize functions of app.cashierGatewayUpdate object
app.cashierGatewayUpdate.init = function() {
    app.cashierGatewayUpdate.ajax();
};

// initialize app.cashierGatewayUpdate object until the document is loaded
$(document).ready(app.cashierGatewayUpdate.init());
