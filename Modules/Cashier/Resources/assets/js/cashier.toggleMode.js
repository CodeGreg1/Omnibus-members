"use strict";

app.toggleCashierGatewayMode = {};

app.toggleCashierGatewayMode.config = {
    button: '.btn-toggle-gateway-mode',
    route: '/admin/settings/update'
};

app.toggleCashierGatewayMode.process = function(e) {
    var value = $(e.target).data('value');
    var dialogToggleCashierMode = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Switching mode') + '...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'PATCH',
        data: {cashier_mode: value},
        url: this.config.route,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                dialogToggleCashierMode.modal('hide');
                window.location = window.location.pathname;
            }, 400);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(function() {
                app.notify(response.responseJSON.message);
                dialogToggleCashierMode.modal('hide');
            }, 400);
        }
    });
};

app.toggleCashierGatewayMode.init = function() {
    $(this.config.button).on('click', this.process.bind(this));
};

$(document).ready(app.toggleCashierGatewayMode.init());
