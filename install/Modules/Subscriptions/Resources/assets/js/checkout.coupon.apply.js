app.applyCouponCheckout = {};

app.applyCouponCheckout.config = {
    button: '#btn-apply-subscription-coupon',
    buttonPay:'.btn-pay-pricing',
    code: '#coupon-code',
    form: '#form-coupon',
    discountId: '#discount',
    route: '/user/subscriptions/coupons/verify',
    priceClass: '.price',
    discount: '.discount',
    couponCode: '.coupon-code',
    price: '#price',
    coupon: '#coupon',
    currency: '#curreny',
    priceId: '#priceId'
};

app.applyCouponCheckout.applyCode = function(code) {
    var currency = $(this.config.currency).val();
    var subTotal = parseFloat($(this.config.price).val());
    var total = subTotal;
    var discountAmount = 0;
    if (code.coupon.amount_type === 'percentage') {
        discountAmount = ((parseFloat(code.coupon.amount) / 100) * subTotal).toFixed(2);
        discountAmount = parseFloat(discountAmount);
    } else {
        discountAmount = parseFloat(code.coupon.amount)
    }
    total = subTotal - discountAmount;
    $(this.config.coupon).val(code.id);
    $(this.config.priceClass).html(currency+total.toLocaleString());
    $(this.config.form).remove();
    $(this.config.discountId).removeClass('none');
    $(this.config.couponCode).html(code.code);
    $(this.config.discount).html('-'+currency+discountAmount);
    // $(this.config.buttonPay).attr('href', $(this.config.buttonPay).data('href') + '?coupon='+code.code);
    $(this.config.buttonPay).map(function() {
        $(this).attr('href', $(this).data('href') + '?coupon='+code.code)
    });
};

app.applyCouponCheckout.process = function(e) {
    var self = this;
    var code = $(this.config.code).val();
    var price = $(this.config.priceId).val();
    var button = $(self.config.button);

    $.ajax({
        type: 'POST',
        url: self.config.route,
        data: {code, price},
        beforeSend: function () {
            button.attr('disabled', true).addClass('loading disabled');
        },
        success: function (response, textStatus, xhr) {
            // button.attr('disabled', false).removeClass('loading disabled');
            console.log(response)
            // if (response.coupon) {
            //     self.applyCode(response.coupon);
            // } else if (response.error) {
            //     bootbox.alert(response.message);
            // }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            button.attr('disabled', false).removeClass('loading disabled');
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.applyCouponCheckout.init = function() {
    $(this.config.button).on('click', this.process.bind(this));
}

app.applyCouponCheckout.init();
