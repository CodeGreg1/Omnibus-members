"use strict";

app.disablePricingTable = {};

app.disablePricingTable.config = {
    button: '.btn-disable-pricing-table'
};

app.disablePricingTable.ajax = function(e) {
    $(document).delegate(this.config.button, 'click', function () {
        let route = $(this).data('route');
        var dialogDisablePricingTable = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Disabling pricing table')+'...</p>',
            closeButton: false
        });

        $.ajax({
            type: 'GET',
            url: route,
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                if (response.data && response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
                setTimeout(function() {
                    dialogDisablePricingTable.modal('hide');
                    if (app.pricingTableDatatable.table) {
                        app.pricingTableDatatable.table.refresh();
                    }
                }, 350);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                setTimeout(function() {
                    dialogDisablePricingTable.modal('hide');
                    app.notify(response.responseJSON.message);
                }, 350);
            }
        });
    });
};

app.disablePricingTable.init = function() {
    this.ajax();
};

$(document).ready(app.disablePricingTable.init());
