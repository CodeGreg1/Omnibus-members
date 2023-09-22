"use strict";

app.cartUpdatePromoCode = {};

app.cartUpdatePromoCode.config = {
    button: '#btn-update-coupon-promo-code',
    form: '#updateCouponPromoCodeForm',
    modal: '#edit-coupon-promo-code-modal',
};

app.cartUpdatePromoCode.data = function() {
    var data = $(this.config.form).serializeArray();
    return data;
};

app.cartUpdatePromoCode.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: $button.data('route'),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.backButtonContent($button, $content);
            $($self.config.modal).modal('hide');

            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }

            setTimeout(() => {
                app.cartPromoCodeDatatable.table.refresh();
            },1000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(() => {
                app.backButtonContent($button, $content);
                app.formErrors($self.config.form, response.responseJSON, response.status);
                // Display error for permissions
                app.roles.groupError(response.responseJSON);
            }, 250);
        }
    });
};

app.cartUpdatePromoCode.init = function() {
    $(this.config.button).click(function() {
        $(this.config.form).submit();
    });
    $(this.config.form).on('submit', this.process.bind(this));
};

$(document).ready(app.cartUpdatePromoCode.init());
