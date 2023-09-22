app.createCouponPackage = {};

app.createCouponPackage.config = {
    button: '.btn-create-coupon-package'
};

app.createCouponPackage.data = function() {
    var packages = $('#add-coupon-package-modal').find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    return {packages};
};

app.createCouponPackage.ajax = function(e) {
    var self = this;
    var button = $(self.config.button);
    var route = button.data('href');
    var content = button.html();
    var data = self.data();
    if (!data.packages.length) {
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
            // Check check if form validation errors
            setTimeout(function() {
                app.notify(response.responseJSON.message);
            }, 350);
        }
    });
};

app.createCouponPackage.init = function() {
    $(this.config.button).on('click', this.ajax.bind(this));
};

$(document).ready(app.createCouponPackage.init());
