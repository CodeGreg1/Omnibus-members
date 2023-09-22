"use strict";

app.cartListUpdateQty = {};

app.cartListUpdateQty.config = {
    quantityInput: '.',
    decrementBtn: '.cart-quantity-decrement',
    incrementBtn: '.cart-quantity-increment',
};

app.cartListUpdateQty.getQuantity = function(e) {
    return parseInt($(e).parents('.cart-quantity-group').find('.cart-quantity-control').val());
};

app.cartListUpdateQty.getRoute = function(id) {
    return '/user/carts/'+id+'/update';
};

app.cartListUpdateQty.ajax = function(id, data) {
    $.ajax({
        type: 'PATCH',
        url: this.getRoute(id),
        data: data,
        beforeSend: function () {
            $('.cart-item[data-id="'+id+'"]').find('.cart-quantity-btn').attr('disabled', true);
        },
        success: function (response, textStatus, xhr) {
            $('.cart-item[data-id="'+id+'"]').find('.cart-quantity-btn').attr('disabled', false);
            app.notify(response.message);
            if ($(app.cartDatatable.config.datatable).length) {
                app.cartDatatable.table.refresh();
            }

            app.cartList.getCartItems();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            $('.cart-item[data-id="'+id+'"]').find('.cart-quantity-btn').attr('disabled', false);
            app.notify(response.responseJSON.message);
        }
    });
};

app.cartListUpdateQty.decrement = function() {
    var self = this;
    $(document).delegate(this.config.decrementBtn, 'click', function() {
        var quantity = self.getQuantity(this);
        var id = $(this).parents('.cart-item').data('id');
        if (quantity > 1 && id) {
            self.ajax(id, {quantity: '-1'});
        }
    });
};

app.cartListUpdateQty.increment = function() {
    var self = this;
    $(document).delegate(this.config.incrementBtn, 'click', function() {
        var id = $(this).parents('.cart-item').data('id');
        if (id) {
            self.ajax(id, {quantity: 1});
        }
    });
};

app.cartListUpdateQty.init = function() {
    if ($(app.cartList.config.dropdownId).length) {
        this.decrement();
        this.increment();
    }
}

$(document).ready(app.cartListUpdateQty.init());
