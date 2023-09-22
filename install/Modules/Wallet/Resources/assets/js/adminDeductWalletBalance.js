"use strict";

app.adminDeductWalletBalance = {};

app.adminDeductWalletBalance.config = {
    btn: '.btn-admin-deduct-wallet-balance',
    modal: '#user-deduct-balance-admin-modal',
    form: '#user-deduct-balance-admin-form',
    route: '/admin/wallet/deduct-balance'
};

// handle app.adminAddWalletBalance object ajax request
app.adminDeductWalletBalance.ajax = function() {
    const $self = this;

    $(app.adminDeductWalletBalance.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminDeductWalletBalance.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminDeductWalletBalance.config.route,
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
                app.formErrors(app.adminDeductWalletBalance.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminDeductWalletBalance.showModal = function() {
    const self = this;

    $(document).delegate(self.config.btn, 'click', function() {
        const balance = $(this).data('balance');
        const currency = $(this).data('currency');

        $(self.config.form).find('.deduct-wallet-currency-label').html(currency);
        $(self.config.form).find('[name="currency"]').val(currency);
        $(self.config.form).find('.deduct-wallet-balance-label').html(balance);
        $(self.config.modal).modal('show');
    });
};

app.adminDeductWalletBalance.onModalHide = function() {
    const self = this;
    $(this.config.modal).on('hide.bs.modal', function () {
        $(self.config.form).find('.deduct-wallet-balance-label').html('$0.00');
        $(self.config.form).find('.deduct-wallet-currency-label').html('USD');
        $(self.config.form).find('[name="currency"]').val('');
        $(self.config.form).find('[name="amount"]').val('');
    });
};

app.adminDeductWalletBalance.init = function() {
    this.showModal();
    this.onModalHide();
    this.ajax();
};

$(document).ready(app.adminDeductWalletBalance.init());
