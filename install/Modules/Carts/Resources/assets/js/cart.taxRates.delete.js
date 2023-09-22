"use strict";

app.deleteTaxRate = {};

app.deleteTaxRate.config = {
    button: '.btn-delete-tax-rate',
    route: '/admin/tax-rates/delete'
};

app.deleteTaxRate.ajax = function(e) {
    var route = $(e.target).data('route');
    var id = $(e.target).data('id');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove tax rate."),
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
                var dialogRemoveTaxRate = bootbox.dialog({
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
                            dialogRemoveTaxRate.modal('hide');
                            if (app.taxRateDatatable.table) {
                                app.taxRateDatatable.table.refresh();
                            }
                        }, 200);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemoveTaxRate.modal('hide');
                        }, 200);
                    }
                });
            }
        }
    });
};

app.deleteTaxRate.init = function() {
    $(document).delegate(this.config.button, 'click', this.ajax.bind(this));
};

$(document).ready(app.deleteTaxRate.init());
