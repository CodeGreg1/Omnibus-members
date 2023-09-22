"use strict";

app.orderTrackingTransition = {};

app.orderTrackingTransition.config = {
    button: '.btn-tracking-transition'
};

app.orderTrackingTransition.route = function(id) {
    return '/admin/orders/'+id+'/update-tracking-state';
};

app.orderTrackingTransition.initTransition = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');

        bootbox.confirm({
            title: app.trans("Update tracking status?"),
            message: app.trans("You are going to update the status. Do you want to proceed?"),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> ' + app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> ' + app.trans('Confirm')
                }
            },
            callback: function (result) {
                if (result) {
                    var dialogUpdatingOrderTrackingStatus = bootbox.dialog({
                        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Updating tracking status')+'...</p>',
                        closeButton: false
                    });

                     $.ajax({
                        type: 'POST',
                        data: {status},
                        url: self.route(id),
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            setTimeout(function() {
                                if (app.userOrderDatatable.table) {
                                    app.userOrderDatatable.table.refresh();
                                }
                                if (app.adminOrderDatatable.table) {
                                    app.adminOrderDatatable.table.refresh();
                                }
                            }, 350);
                        },
                        complete: function (xhr) {
                            setTimeout(function() {
                                dialogUpdatingOrderTrackingStatus.modal('hide');
                            }, 350);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            app.notify(response.responseJSON.message);
                            setTimeout(function() {
                                dialogUpdatingOrderTrackingStatus.modal('hide');
                            }, 350);
                        }
                    });
                }
            }
        });
    });
};

app.orderTrackingTransition.init = function() {
    this.initTransition();
}

$(document).ready(app.orderTrackingTransition.init());
