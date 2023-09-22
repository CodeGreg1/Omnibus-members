"use strict";

app.deletePricingTable = {};

app.deletePricingTable.config = {
    route: '/admin/subscriptions/pricing-tables/delete'
};

app.deletePricingTable.ajax = function(items) {
    const self = this;

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove pricing table."),
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
                var dialogRemovePricingTable = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: self.config.route,
                    data: {items},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        if (response.data && response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                        setTimeout(function() {
                            dialogRemovePricingTable.modal('hide');
                            if (app.pricingTableDatatable.table) {
                                app.pricingTableDatatable.table.refresh();
                            }
                        }, 350);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemovePricingTable.modal('hide');
                            app.notify(response.responseJSON.message);
                        }, 350);
                    }
                });
            }
        }
    });
};
