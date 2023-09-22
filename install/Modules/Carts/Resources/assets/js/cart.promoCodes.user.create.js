"use strict";

app.cartCreateUserPromoCode = {};

app.cartCreateUserPromoCode.config = {
    button: '.btn-create-coupon-user'
};

app.cartCreateUserPromoCode.data = function() {
    var users = $('#add-coupon-user-modal').find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    return {users};
};

app.cartCreateUserPromoCode.ajax = function(e) {
    var self = this;
    var button = $(self.config.button);
    var route = button.data('href');
    var content = button.html();
    var data = self.data();
    if (!data.users.length) {
        return;
    }

    $.ajax({
        type: 'POST',
        url: route,
        data: data,
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                app.closeModal();
                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            }, 500);
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            button.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            app.notify(response.responseJSON.message);
            // Check check if form validation errors
        }
    });
};

app.cartCreateUserPromoCode.init = function() {
    $(this.config.button).on('click', this.ajax.bind(this));
};

$(document).ready(app.cartCreateUserPromoCode.init());
