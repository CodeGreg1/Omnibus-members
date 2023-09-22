"use strict";

app.cancelOrder = {};

app.cancelOrder.config = {
    button: '.btn-cancel-order'
};

app.cancelOrder.process = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function() {
        const route = $(this).data('route');
        bootbox.prompt({
            title: app.trans("Reason to cancel (optional)"),
            inputType: 'textarea',
            callback: function (result) {
                if (result !== null) {
                    var dialogCancelUserOrder= bootbox.dialog({
                        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Cancelling order')+'...</p>',
                        closeButton: false
                    });

                    $.ajax({
                        type: 'POST',
                        url: route,
                        data: {reason: result},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            if (app.userOrderDatatable.table) {
                                app.userOrderDatatable.table.refresh();
                            } else {
                                if (response.data.redirectTo) {
                                    app.redirect(response.data.redirectTo);
                                }
                            }

                            setTimeout(function() {
                                dialogCancelUserOrder.modal('hide');
                            }, 350);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            setTimeout(function() {
                                dialogCancelUserOrder.modal('hide');
                                app.notify(response.responseJSON.message);
                            }, 350);
                        }
                    });
                }
            }
        });
    });
};

app.cancelOrder.init = function() {
    app.cancelOrder.process();
};

$(document).delegate(app.cancelOrder.init());
