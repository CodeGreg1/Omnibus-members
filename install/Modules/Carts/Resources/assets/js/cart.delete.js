"use strict";

app.deleteCartItem = {};

app.deleteCartItem.config = {
    button: '.cart-remove-item',
    route: '/user/carts/delete'
};

app.deleteCartItem.ajax = function(e) {
    var self = this;
    var id = $(e.target).data('id');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove selected item from cart."),
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
                var dialogRemoveCartItem = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing cart item')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    data: {items: [id]},
                    url: self.config.route,
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        setTimeout(function() {
                            dialogRemoveCartItem.modal('hide');
                            if ($(app.cartDatatable.config.datatable).length) {
                                app.cartDatatable.table.refresh();
                            }

                            app.cartList.getCartItems();
                        }, 200);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        app.notify(response.responseJSON.message);
                        setTimeout(function() {
                            dialogRemoveCartItem.modal('hide');
                        }, 200);
                    }
                });
            }
        }
    });
};

app.deleteCartItem.init = function() {
    $(document).delegate(this.config.button, 'click', this.ajax.bind(this));
}

$(document).ready(app.deleteCartItem.init());
