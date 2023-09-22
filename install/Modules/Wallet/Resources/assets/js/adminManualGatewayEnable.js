"use strict";

// define app.adminManualGatewayEnable object
app.adminManualGatewayEnable = {};

// handle app.adminManualGatewayEnable object configuration
app.adminManualGatewayEnable.config = {
    button: '.btn-enable-manual-gateway',
    route: '/admin/manual-gateways/enable'
};

// handle app.adminManualGatewayEnable object ajax request
app.adminManualGatewayEnable.ajax = function(e) {
    const self = this;
    $(document).delegate(app.adminManualGatewayEnable.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to enable this manual gateway!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {

                    var actionButton = button.parents('.tb-actions-column').find('.dropdown-toggle');
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'POST',
                        url: self.config.route,
                        data: {resources: [button.attr('data-id')]},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            setTimeout(() => {
                                app.notify(response.message);
                                if (app.adminDepositManualGatewayDatatable.table) {
                                    app.adminDepositManualGatewayDatatable.table.refresh();
                                }
                                if (app.adminWithdrawMethodsDatatable.table) {
                                    app.adminWithdrawMethodsDatatable.table.refresh();
                                }
                            }, 350);
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                        }
                    });
                }
            }
        });
    });

};

// initialize functions of app.adminManualGatewayEnable object
app.adminManualGatewayEnable.init = function() {
    app.adminManualGatewayEnable.ajax();
};

// initialize app.adminManualGatewayEnable object until the document is loaded
$(document).ready(app.adminManualGatewayEnable.init());
