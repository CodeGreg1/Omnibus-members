"use strict";

app.addCartItem = {};

app.addCartItem.config = {
    button: '.btn-add-single-cart-item',
    route: '/user/carts/create',
};

app.addCartItem.ajax = function(items, btn = null) {
    var self = this;

    $.ajax({
        type: 'POST',
        data: {items},
        url: self.config.route,
        beforeSend: function() {
            if (btn) {
                btn.attr('disabled', true).addClass('disabled btn-progress');
            }
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
            setTimeout(function() {
                if ($(app.cartList.config.dropdownId).length) {
                    app.cartList.getCartItems();
                }
            }, 200);
        },
        complete: function (xhr) {
            if (btn) {
                btn.attr('disabled', false).removeClass('disabled btn-progress');
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            app.notify(response.responseJSON.message);
        }
    });
};

app.addCartItem.addSingleItem = function(e) {
    var self = this;
    var btn = $(e.target);

    self.ajax(
        [
            {
                'object': btn.data('object'),
                'key': btn.data('key'),
                'quantity': 1
            }
        ]
    );
};

app.addCartItem.init = function() {
    $(document).delegate(this.config.button, 'click', this.addSingleItem.bind(this));
}

$(document).ready(app.addCartItem.init());
