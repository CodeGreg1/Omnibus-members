app.adminProductsDelete = {};

app.adminProductsDelete.config = {
    button: '.btn-admin-products-delete'
};

app.adminProductsDelete.ajax = function(e) {

    $(document).delegate(app.adminProductsDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this product!"),
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
                        url: '/admin/products/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminProductsDatatable.tb.refresh();
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

app.adminProductsDelete.init = function() {
    app.adminProductsDelete.ajax();
};

$(document).ready(app.adminProductsDelete.init());