"use strict";

// define adminAvailableCurrenciesDelete object
app.adminAvailableCurrenciesDelete = {};

// handle adminAvailableCurrenciesDelete object configuration
app.adminAvailableCurrenciesDelete.config = {
    button: '.btn-admin-availablecurrencies-delete'
};

// handle adminAvailableCurrenciesDelete object ajax request
app.adminAvailableCurrenciesDelete.ajax = function(e) {

    $(document).delegate(app.adminAvailableCurrenciesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this available currency!"),
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
                    $.ajax({
                        type: 'DELETE',
                        url: '/admin/currencies/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminAvailableCurrenciesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
     
};

// call all available functions of adminAvailableCurrenciesDelete object
app.adminAvailableCurrenciesDelete.init = function() {
    app.adminAvailableCurrenciesDelete.ajax();
};

// initialize adminAvailableCurrenciesDelete object until the document is loaded
$(document).ready(app.adminAvailableCurrenciesDelete.init());