"use strict";

app.adminAddWalletBalance = {};

// handle app.adminAddWalletBalance object configuration
app.adminAddWalletBalance.config = {
    form: '#user-add-balance-admin-form',
    route: '/admin/wallet/add-balance'
};

// handle app.adminAddWalletBalance object ajax request
app.adminAddWalletBalance.ajax = function() {
    const $self = this;

    $(app.adminAddWalletBalance.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminAddWalletBalance.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminAddWalletBalance.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminAddWalletBalance.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

// initialize functions of app.adminAddWalletBalance object
app.adminAddWalletBalance.init = function() {
    app.adminAddWalletBalance.ajax();
};

// initialize app.adminAddWalletBalance object until the document is loaded
$(document).ready(app.adminAddWalletBalance.init());
