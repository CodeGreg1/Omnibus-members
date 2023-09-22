"use strict";

app.deleteCartCoupon = {};

app.deleteCartCoupon.config = {
    button: '.btn-delete-coupon'
};


app.deleteCartCoupon.ajax = function(e) {
    var route = $(e.target).data('route');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove coupon."),
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
                var dialogRemovePromoCode = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        if (response.data && response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                        setTimeout(function() {
                            dialogRemovePromoCode.modal('hide');
                            if (app.cartCouponDatatable.table) {
                                app.cartCouponDatatable.table.refresh();
                            }
                        }, 350);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemovePromoCode.modal('hide');
                            app.notify(response.responseJSON.message);
                        }, 350);
                    }
                });
            }
        }
    });
};

app.deleteCartCoupon.init = function() {
    $(document).delegate(this.config.button, 'click', this.ajax.bind(this));
};

$(document).ready(app.deleteCartCoupon.init());
