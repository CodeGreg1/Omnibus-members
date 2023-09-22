"use strict";

// define app.adminManualGatewayDisable object
app.adminManualGatewayDisable = {};

// handle app.adminManualGatewayDisable object configuration
app.adminManualGatewayDisable.config = {
    button: '.btn-disable-manual-gateway',
    route: '/admin/manual-gateways/disable'
};

// handle app.adminManualGatewayDisable object ajax request
app.adminManualGatewayDisable.ajax = function(e) {
    const self = this;
    $(document).delegate(app.adminManualGatewayDisable.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to disable this manual gateway!"),
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

// initialize functions of app.adminManualGatewayDisable object
app.adminManualGatewayDisable.init = function() {
    app.adminManualGatewayDisable.ajax();
};

// initialize app.adminManualGatewayDisable object until the document is loaded
$(document).ready(app.adminManualGatewayDisable.init());
