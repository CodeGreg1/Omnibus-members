"use strict";

app.enablePricingTable = {};

app.enablePricingTable.config = {
    button: '.btn-activate-pricing-table'
};

app.enablePricingTable.ajax = function(e) {
    $(document).delegate(this.config.button, 'click', function () {
        let route = $(this).data('route');
        var dialogEnablePricingTable = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Enabling pricing table')+'...</p>',
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
                    dialogEnablePricingTable.modal('hide');
                    if (app.pricingTableDatatable.table) {
                        app.pricingTableDatatable.table.refresh();
                    }
                }, 350);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                setTimeout(function() {
                    dialogEnablePricingTable.modal('hide');
                    app.notify(response.responseJSON.message);
                }, 350);
            }
        });
    });
};

app.enablePricingTable.init = function() {
    this.ajax();
};

$(document).ready(app.enablePricingTable.init());
