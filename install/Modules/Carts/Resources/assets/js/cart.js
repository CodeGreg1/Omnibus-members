"use strict";

app.cart = {};

app.cart.config = {
    checkoutBtn: '#btn-cart-checkout',
};

app.cart.toggleCheckoutBtn = function(e, tableApi) {
    var self = this;
    var items = tableApi.selected();
    var itemIds = items.map(item => item.id);
    var route = $(self.config.checkoutBtn).data('href');
    if (itemIds.length) {
        route = route + '?items=' + itemIds.join(',');
        $(self.config.checkoutBtn).removeClass('disabled');
    } else {
        $(self.config.checkoutBtn).addClass('disabled');
    }
    $(self.config.checkoutBtn).attr('href', route);
};

app.cart.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(app.cartDatatable.config.datatable).length) {
        $(app.cartDatatable.config.datatable).on('table:selection', this.toggleCheckoutBtn.bind(this));
        $(app.cartDatatable.config.datatable).on('table:draw.finish', this.toggleCheckoutBtn.bind(this));
    }
};

$(document).ready(function() {
    app.cart.init();

    $('#cart-notification-dopdown').on('hide.bs.dropdown', function (e) {
        var target = $(e.target);
        if((target.hasClass("keepopen")
        || target.parents(".keepopen").length)
        && ($(e.clickEvent.target).parents('.dropdown-list-content').length || $(e.clickEvent.target).hasClass('dropdown-list-content'))){
            return false; // returning false should stop the dropdown from hiding.
        }else{
            return true;
        }
    });

    if ($('#checkout-thank-you-modal').length) {
        $('#checkout-thank-you-modal').modal('show');
    }

    if (window.autosize) {
        if ($('.autosize').length) {
            autosize($('.autosize'));
        }
    }
});
