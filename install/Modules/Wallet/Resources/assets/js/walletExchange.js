"use strict";

app.walletExchange = {};

app.walletExchange.config = {
    data: null,
    modal: '#wallet-exchange-modal',
    form: '#wallet-exchange-form',
    route: '/profile/wallet/exchange/process',
    checkoutRoute: '/profile/wallet/exchange/checkout'
};

app.walletExchange.onModalShow = function() {
    const self = this;
    $(this.config.modal).on('show.bs.modal', function () {
        const currency = $(self.config.form).find('[name="from_currency"]').val();
        if (currency) {
            $(self.config.form).find('[name="amount"]').val('');
            $(self.config.form).find('[name="to_currency"]').val('');
            $(self.config.form).find('[name="to_currency"]')
                .find('option')
                .prop('disabled',false);
            $(self.config.form).find('[name="to_currency"]')
                .find('option[value="'+currency+'"]')
                .prop('disabled',true);
            $(self.config.form+' [name="to_currency"]').select2();
        }

        $(self.config.form).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(self.config.form).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(self.config.form).find(app.removeClassDot(app.formErrorsHelper.config.formErrorMessageClass)).remove();
    })
};

app.walletExchange.ajax = function() {
    const self = this;

    $(self.config.form).on('submit', function(e) {
        e.preventDefault();

        var $button = $(self.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: self.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                $(self.config.modal).modal('hide');
                self.config.data = response.data;
                self.checkout();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // Reset button
                app.backButtonContent($button, $content);
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(self.config.form, response.responseJSON, response.status);
            }
        });
    });
};

app.walletExchange.checkout = function() {
    const data = this.config.data;
    if (data) {
        const message = `<ul class="list-group mb-4">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Rate')}</strong>
                                <strong>
                                    ${app.trans('Rate')}: ${data.currency_rate[data.to.currency]} ${data.to.currency} ~ ${data.currency_rate[data.from.currency]} ${data.from.currency}
                                </strong>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${data.from.currency_name}</strong>

                                <strong class="d-flex align-items-center">
                                    <span>${data.from.amount_display}</span>
                                </strong>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${data.to.currency_name}</strong>

                                <strong class="d-flex align-items-center">
                                    <span>${data.to.reg_amount}</span>
                                </strong>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Receivable')}</strong>
                                <h3 class="wallet-balance text-success mb-0">
                                    ${data.to.amount_display}
                                </h3>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <strong class="mr-1">${app.trans('Charges')} </strong>
                                    (<strong class="d-flex align-items-center text-danger">
                                        <span>${data.fixed_charge_display}</span>
                                        <span class="mx-1">+</span>
                                        <span>${data.rate}%</span>
                                    </strong>)
                                </div>

                                <strong class="d-flex align-items-center text-danger">
                                    <span class="wallet-exchange-fixed-amount">${data.fixed_charge_display}</span>
                                    <span class="mx-1">+</span>
                                    <span class="wallet-exchange-rate">${data.rate_charge_display}</span>
                                </strong>
                            </div>
                        </li>
                    </ul>`;

        const dialog = bootbox.dialog({
            title: app.trans('Wallet exchange details'),
            message: message,
            buttons: {
                cancel: {
                    label: app.trans("Cancel"),
                    className: 'btn-danger'
                },
                ok: {
                    label: app.trans("Convert now"),
                    className: 'btn-primary',
                    callback: function(){
                        var convertingDialog = bootbox.dialog({
                            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Processing')+'...</p>',
                            closeButton: false
                        });

                        if (window.allow_wallet_exchange_otp) {
                            app.walletOtp.confirm({
                                success: function(response) {
                                    const data = app.walletExchange.config.data;
                                    app.walletExchange.config.data = null;
                                    $.ajax({
                                        type: 'POST',
                                        url: app.walletExchange.config.checkoutRoute,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function (response, textStatus, xhr) {
                                            app.walletExchange.config.data = null;
                                            setTimeout(function() {
                                                app.notify(response.message);
                                                app.redirect(response.data.redirectTo);
                                            }, 350);
                                        },
                                        error: function (response, textStatus, errorThrown) {
                                            app.walletExchange.config.data = data;
                                            setTimeout(function() {
                                                app.notify(response.responseJSON.message);
                                            }, 350);
                                        }
                                    });
                                },
                                error: function(response) {
                                    app.notify(response.responseJSON.message);
                                },
                                always: function() {
                                    convertingDialog.modal('hide');
                                }
                            }, 'wallet_exchange_checkout');
                        } else {
                            var $button = $('.wallet-exchange-link');
                            var $content = $button.html();
                            const data = app.walletExchange.config.data;
                            app.walletExchange.config.data = null;

                            $.ajax({
                                type: 'POST',
                                url: app.walletExchange.config.checkoutRoute,
                                contentType: false,
                                cache: false,
                                processData: false,
                                beforeSend: function() {
                                    app.buttonLoader($button);
                                },
                                success: function (response, textStatus, xhr) {
                                    app.backButtonContent($button, $content);
                                    app.walletExchange.config.data = null;
                                    setTimeout(function() {
                                        app.notify(response.message);
                                        app.redirect(response.data.redirectTo);
                                    }, 350);
                                },
                                error: function (response, textStatus, errorThrown) {
                                    app.backButtonContent($button, $content);
                                    app.walletExchange.config.data = data;
                                    setTimeout(function() {
                                        app.notify(response.responseJSON.message);
                                    }, 350);
                                }
                            });
                        }
                    }
                }
            }
        });
    }
};

app.walletExchange.onOtpCancel = function() {
    $(app.walletOtp.config.modal).on('hide.bs.modal', function (event) {
        setTimeout(function() {
            app.walletExchange.checkout();
        }, 350);
    })
};

app.walletExchange.init = function() {
    this.onModalShow();
    this.ajax();

    this.onOtpCancel();
};

$(document).ready(app.walletExchange.init());
