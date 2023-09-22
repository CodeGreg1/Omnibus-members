"use strict";

app.adminWalletSettings = {};

// handle app.adminWalletSettings object configuration
app.adminWalletSettings.config = {
    form: '#admin-wallet-settings-edit-form',
    route: '/admin/settings/wallet/update',
    redirect: '/admin/settings/wallet'
};

// handle app.adminWalletSettings object ajax request
app.adminWalletSettings.ajax = function() {

    $(app.adminWalletSettings.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminWalletSettings.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminWalletSettings.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);

                setTimeout(function() {
                    app.redirect(app.adminWalletSettings.config.redirect);
                }, 1000);

                app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminWalletSettings.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });


    });

};

app.adminWalletSettings.allowMultiWalletSettingChange = function() {
    $('#allow_wallet_multi_currency').on('change', function() {
        if (this.value === 'enable') {
            $('.wallet-charge-settings').find('tbody tr').removeClass('d-none');
        } else {
            const currency = $('.wallet-charge-settings').data('system-currency');
            $('.wallet-charge-settings').find('tbody tr').map(function() {
                if ($(this).data('currency') === currency) {
                    $(this).removeClass('d-none');
                } else {
                    $(this).addClass('d-none');
                }
            });
        }
    });
};

// initialize functions of app.adminWalletSettings object
app.adminWalletSettings.init = function() {
    app.adminWalletSettings.ajax();

    app.adminWalletSettings.allowMultiWalletSettingChange();
};

// initialize app.adminWalletSettings object until the document is loaded
$(document).ready(app.adminWalletSettings.init());
