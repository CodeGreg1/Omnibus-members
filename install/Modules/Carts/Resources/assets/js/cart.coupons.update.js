"use strict";

app.updateCartCoupon = {};

app.updateCartCoupon.config = {
    button: '#btn-update-coupon',
    form: '#updateCouponForm'
};

app.updateCartCoupon.loadSelectedPackages = function(packages) {
    if (packages.length) {
        app.createCartCoupon.config.plans.items = packages.map(function(item) {
            return item.id.toString();
        });

        for (let index = 0; index < packages.length; index++) {
            const item = packages[index];
            var input = $(app.createCartCoupon.config.plans.wrapper).find('.ts-control');
            var html = `<div class="py-2 d-flex w-100" data-value="${ item.id }" data-ts-item>
							<div class="icon me-3">
								<img class="mr-3 rounded" width="40" src="/themes/stisla/assets/img/products/product-4-50.png" alt="product">
							</div>
							<div class="d-flex align-items-center">
                                <strong>
                                    ${ item.name }
                                </strong>
							</div>
						</div>`;
            input.prepend(html);
        }
    }
};

app.updateCartCoupon.data = function() {
    var data = $(this.config.form).serializeArray();
    var plans = $(this.config.form).find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    if (plans.length) {
        data.push({
            name: 'plans',
            value: plans
        });
    }

    return data;
};

app.updateCartCoupon.data = function() {
    var data = $(this.config.form).serializeArray();
    var plans = $(this.config.form).find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    if (plans.length) {
        data.push({
            name: 'plans',
            value: plans
        });
    }

    return data;
};

app.updateCartCoupon.ajax = function() {
    var self = this;
    var button = $(self.config.button);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();

    $.ajax({
        type: 'PATCH',
        url: button.data('route'),
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            }, 500);
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            formSubmit.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.updateCartCoupon.submitForm = function() {
    var self = this;
    $(self.config.button).on('click', self.ajax.bind(self));
};

app.updateCartCoupon.init = function() {
    this.submitForm();
};

$(document).ready(app.updateCartCoupon.init());
