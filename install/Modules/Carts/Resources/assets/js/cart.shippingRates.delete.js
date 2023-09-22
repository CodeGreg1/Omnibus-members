"use strict";

app.deleteShippingRate = {};

app.deleteShippingRate.config = {
    button: '.btn-delete-shipping-rate',
    route: '/admin/shipping-rates/delete'
};

app.deleteShippingRate.ajax = function(e) {
    var route = $(e.target).data('route');
    var id = $(e.target).data('id');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove shipping rate."),
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
                var dialogRemoveShippingRate = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    data: {rates: [id]},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        if (response.data && response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                        setTimeout(function() {
                            dialogRemoveShippingRate.modal('hide');
                            if (app.shippingRateDatatable.table) {
                                app.shippingRateDatatable.table.refresh();
                            }
                        }, 200);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemoveShippingRate.modal('hide');
                        }, 200);
                    }
                });
            }
        }
    });
};

app.deleteShippingRate.init = function() {
    $(document).delegate(this.config.button, 'click', this.ajax.bind(this));
};

$(document).ready(app.deleteShippingRate.init());
