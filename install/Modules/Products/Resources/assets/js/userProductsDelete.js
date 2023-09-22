app.userProductsDelete = {};

app.userProductsDelete.config = {
    button: '.btn-user-products-delete'
};

app.userProductsDelete.ajax = function(e) {

    $(document).delegate(app.userProductsDelete.config.button, 'click', function() {

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
                        url: '/user/products/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.userProductsDatatable.tb.refresh();
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

app.userProductsDelete.init = function() {
    app.userProductsDelete.ajax();
};

$(document).ready(app.userProductsDelete.init());