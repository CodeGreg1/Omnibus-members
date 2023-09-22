"user strict";

app.cartList = {};

app.cartList.config = {
    dropdownId: '#cart-notification-dopdown',
    route: '/user/carts/notification-items',
    itemCount: '.cart-items-count'
};

app.cartList.itemHtml = function(item)
{
    var html = '<div class="dropdown-item d-block">';
    html += '<div class="media cart-item" data-id="'+item.id+'">';
    html += '<img class="mr-3 rounded" width="55" src="'+item.image+'" alt="product">';
    html += '<div class="media-body text-dark lh-base">';
    html += '<a href="'+item.purchasablePath+'" class="media-title w-14rem">'+item.name+'</a>';

    /** Item description  */
        html += '<div>';
        html += '<div class="space-initial line-clamp-2">';
        html += item.description;
        html += '</div>';
        html += '</div>';
    /** End Item description  */

    /** Item price and quantity  */
    html += '<div class="mt-1 d-flex justify-content-between align-items-center">';
    html += '<div class="cart-quantity-group">';
    html += '<button type="button" class="cart-quantity-btn cart-quantity-decrement">';
    html += '<i class="fas fa-minus"></i></button>';
    html += '<input type="number" class="cart-quantity-control" readonly value="'+item.quantity+'">';
    html += '<button type="button" class="cart-quantity-btn cart-quantity-increment">';
    html += '<i class="fas fa-plus"></i></button>';
    html += '</div>';

    html += '<div class="d-flex flex-column align-items-end">';
    if (item.price !== item.total) {
        html += '<span class="text-sm text-muted mb-1">'+item.price+' each</span>';
    }
    html += '<h6 class="mb-0">'+item.total+'</h6>';
    html += '</div>';
    html += '</div>';
    /** End Item price and quantity  */

    html += '</div>';
    html += '</div></div>';
    return html;
};

app.cartList.emptyListHtml = function() {
    var html = '<div class="dropdown-item d-block">';
    html += '<div class="text-center pt-4 text-muted">'+app.trans('Your cart is empty.')+'</div>';
    html += '</div>';
    html += '<div class="dropdown-item">';
    html += '<div class="d-flex align-items-center justify-content-center mb-2 w-100">';
    html += '<a href="#" class="btn btn-primary">'+app.trans('Show now')+'</a></div></div>';
    return html;
};

app.cartList.loadList = function(cart) {
    $(this.config.dropdownId).find('.dropdown-list-content').html('');
    $(this.config.dropdownId).find('.notification-toggle').toggleClass('beep', cart.count ? true : false);
    $(this.config.dropdownId).find('.dropdown-footer').toggleClass('d-none', !cart.count);
    $(this.config.dropdownId).find('.cart-view-all').toggleClass('d-none', !cart.count);
    if (cart.items.length && cart.count) {
        $(this.config.itemCount).html(cart.count);

        for (let index = 0; index < cart.items.length; index++) {
            const item = cart.items[index];
            $(this.config.dropdownId).find('.dropdown-list-content').append(this.itemHtml(item));
        }
    } else {
        $(this.config.dropdownId).find('.dropdown-list-content').html(this.emptyListHtml());
    }
};

app.cartList.getCartItems = function() {
    var self = this;

    $.ajax({
        type: 'GET',
        url: self.config.route,
        beforeSend: function () {
        },
        success: function (response, textStatus, xhr) {
            self.loadList(response);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
        }
    });
};

app.cartList.init = function() {
    if ($(this.config.dropdownId).length) {
        this.getCartItems();
    }
}

$(document).ready(app.cartList.init());
