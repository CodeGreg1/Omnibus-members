"use strict";

// define app.adminManualGatewayDelete object
app.adminManualGatewayDelete = {};

// handle app.adminManualGatewayDelete object configuration
app.adminManualGatewayDelete.config = {
    button: '.btn-delete-manual-gateway',
    route: '/admin/manual-gateways/delete'
};

// handle app.adminManualGatewayDelete object ajax request
app.adminManualGatewayDelete.ajax = function(e) {
    const self = this;
    $(document).delegate(app.adminManualGatewayDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this manual gateway!"),
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
                        type: 'DELETE',
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

// initialize functions of app.adminManualGatewayDelete object
app.adminManualGatewayDelete.init = function() {
    app.adminManualGatewayDelete.ajax();
};

// initialize app.adminManualGatewayDelete object until the document is loaded
$(document).ready(app.adminManualGatewayDelete.init());
