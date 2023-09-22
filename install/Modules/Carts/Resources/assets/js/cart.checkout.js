"use strict";

app.cartCheckout = {};

app.cartCheckout.config = {
    route: {
        update: window.location.pathname,
        pay: window.location.pathname + '/checkout-process',
        validateCoupon: window.location.pathname + '/validate-coupon'
    },
    submit: '#btn-submit-checkout',
    form: '#checkout-form',
    shippingAddressForm: '#checkout-newShippingAddressForm',
    billingAddressForm: '#checkout-newBillingAddressForm',
    couponCode: '#checkout_coupon',
    couponForm: '#checkout-coupon-form',
    couponSubmit: '.checkout-coupon-action',
    couponRemove: '.checkout-remove-coupon'
};

app.cartCheckout.reloadBreakdown = function(breakdown) {
    $('.checkout-total').html(breakdown.total);
    $('.checkout-subtotal').html(breakdown.subtotal);
    if (breakdown.discount) {
        $('.checkout-discount-title').html(breakdown.discount.title);
        $('.checkout-discount-amount').html(breakdown.discount.amount);
        $('.checkout-discount-description').html(breakdown.discount.description);
        $('.checkout-discount-row').removeClass('d-none');
        $(this.config.couponForm).addClass('d-none');
    } else {
        $('.checkout-discount-row').addClass('d-none');
        $(this.config.couponForm).removeClass('d-none');
    }

    if (breakdown.shippingRate) {
        $('.checkout-shipping-rate-title').html(breakdown.shippingRate.title);
        $('.checkout-shipping-rate-amount').html(breakdown.shippingRate.price);
    }

    if (breakdown.taxRates) {
        Object.keys(breakdown.taxRates).forEach(function(key) {
            var element = $('[data-name="'+key+'"]');
            element.find('.checkout-tax-rate-amount').html(breakdown.taxRates[key]);
        });
    }
};

app.cartCheckout.handleShippingMethod = function() {
    var self = this;
    $('[name="shipping_rate_id"]').on('change', function(e) {
        var data = {
            fields: {
                shipping_rate_id: $(this).val()
            },
            breakdown: true
        };

        var dialogChangeShippingRate = bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Changing shipping rate')+'...</p>',
            closeButton: false
        });

        $.ajax({
            type: 'PATCH',
            data: data,
            url: self.config.route.update,
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                setTimeout(function() {
                    dialogChangeShippingRate.modal('hide');
                    if (response.data.breakdown) {
                        self.reloadBreakdown(response.data.breakdown);
                    }
                }, 300);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                app.notify(response.responseJSON.message);
                setTimeout(function() {
                    dialogChangeShippingRate.modal('hide');
                }, 300);
            }
        });
    });
};

app.cartCheckout.handleAddCoupon = function() {
    var self = this;
    $(self.config.couponSubmit).on('click', function(e) {
        var button = $(e.target);
        var code = $(self.config.couponCode).val();

        if (code.trim()) {
            $.ajax({
                type: 'POST',
                url: self.config.route.validateCoupon,
                data: {checkout_coupon: code, breakdown: true},
                beforeSend: function () {
                    button.attr('disabled', true).addClass('loading disabled');
                },
                success: function (response, textStatus, xhr) {
                    button.attr('disabled', false).removeClass('loading disabled');
                    app.notify(response.message);
                    setTimeout(function() {
                        if (response.data.breakdown) {
                            self.reloadBreakdown(response.data.breakdown);
                        }
                    }, 300);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    var response = XMLHttpRequest;
                    button.attr('disabled', false).removeClass('loading disabled');
                    app.formErrors(self.config.couponForm, response.responseJSON, response.status);
                }
            });
        }
    });
};

app.cartCheckout.handleRemoveCoupon = function() {
    var self = this;
    $(self.config.couponRemove).on('click', function(e) {
        var data = {
            fields: {
                promo_code_id: null
            },
            breakdown: true
        };

        var dialogRemoveCoupon= bootbox.dialog({
            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing promo code')+'...</p>',
            closeButton: false
        });

        $.ajax({
            type: 'PATCH',
            data: data,
            url: self.config.route.update,
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                setTimeout(function() {
                    dialogRemoveCoupon.modal('hide');
                    if (response.data.breakdown) {
                        self.reloadBreakdown(response.data.breakdown);
                    }
                }, 300);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                app.notify(response.responseJSON.message);
                setTimeout(function() {
                    dialogRemoveCoupon.modal('hide');
                }, 300);
            }
        });
    });
};

app.cartCheckout.data = function() {
    var data = {
        gateway: $('[name="payment_method"]:checked').val(),
        note: $('[name="note"]').val()
    };

    if ($('#phone').length) {
        data.phone = $('#phone').val();
    }

    if ($(this.config.shippingAddressForm).length) {
        var shippingAddressForm = $(this.config.shippingAddressForm).serializeArray();
        var shippingAddressId = $('[name="shipping_address_id"]:checked').val();
        if (shippingAddressId === '0' || !$('.shipping-addresses .list-select-control').length) {
            data.shipping_address = {};
            for (let index = 0; index < shippingAddressForm.length; index++) {
                const shippingAddress = shippingAddressForm[index];
                data.shipping_address[shippingAddress.name] = shippingAddress.value;
            }
        } else {
            data.shipping_address_id = shippingAddressId;
        }
    }

    if ($(this.config.billingAddressForm).length) {
        var billingAddressForm = $(this.config.billingAddressForm).serializeArray();
        if ($('#billing_address_same').length && $('[name="billing_address_same"]:checked').length) {
            data.billing_address_same = 1;
        } else {
            var billingAddressId = $('[name="billing_address_id"]:checked').val();
            if (billingAddressId === '0') {
                data.billing_address = {};
                for (let index = 0; index < billingAddressForm.length; index++) {
                    const billingAddress = billingAddressForm[index];
                    data.billing_address[billingAddress.name] = billingAddress.value;
                }
            } else {
                data.billing_address_id = billingAddressId;
            }
        }
    }

    return data;
};

app.cartCheckout.delegateFormErrors = function({message, errors}) {
    const formErrrors = {};
    const keys = Object.keys(errors);
    for (let index = 0; index < keys.length; index++) {
        const key = keys[index];
        const error = errors[key];
        const arrayKey = key.split('.');
        if (arrayKey.length > 1) {
            if (!formErrrors[arrayKey[0]]) {
                formErrrors[arrayKey[0]] = {};
            }

            formErrrors[arrayKey[0]][arrayKey[1]] = error;
        }
    }

    Object.keys(errors).map(function(k, i) {
        if (k === 'shipping_address') {
            app.formErrors(self.config.shippingAddressForm, {message, errors: errors[k]}, 422);
        }
        if (k === 'billing_address') {
            app.formErrors(self.config.billingAddressForm, {message, errors: errors[k]}, 422);
        }
    });
};

app.cartCheckout.process = function(e) {
	var self = this;
    var button = $(e.target);

    $.ajax({
        type: 'POST',
        url: self.config.route.pay,
        data: self.data(),
        beforeSend: function () {
            button.attr('disabled', true).addClass('disabled btn-progress');
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                if (response.message) {
                    app.notify(response.message);
                }

                if (response.data.location) {
                    window.location = response.data.location;
                }
            }, 500);
        },
        complete: function (xhr) {
            button.attr('disabled', false).removeClass('disabled btn-progress');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(function() {
                app.notify(response.responseJSON.message);
            }, 350);
            app.formErrors(self.config.form, response.responseJSON, response.status);
            if (response.responseJSON && response.responseJSON.errors) {
                self.delegateFormErrors(response.responseJSON);
            }
        }
    });
};

app.cartCheckout.handleBillingAddress = function() {
    var self = this;
    $('[name="billing_address_same"]').on('change', function() {
        if (this.checked) {
            $('.billing-addresses').addClass('d-none');
        } else {
            $('.billing-addresses').removeClass('d-none');
        }
    });
};

app.cartCheckout.init = function() {
    this.handleShippingMethod();
    this.handleAddCoupon();
    this.handleRemoveCoupon();
    this.handleBillingAddress();

    $(this.config.submit).on('click', this.process.bind(this));
}

$(document).ready(app.cartCheckout.init());
