"use strict";

app.walletSendMoney = {};

app.walletSendMoney.config = {
    data: null,
    modal: '#wallet-send-money-modal',
    form: '#wallet-send-money-form',
    route: '/profile/wallet/send-money/process',
    checkoutRoute: '/profile/wallet/send-money/checkout'
};

app.walletSendMoney.onModalShow = function() {
    const self = this;
    $(this.config.modal).on('show.bs.modal', function () {
        $(self.config.form).find('[name="amount"]').val('');
        $(self.config.form).find('[name="email"]').val('');

        $(self.config.form).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(self.config.form).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(self.config.form).find(app.removeClassDot(app.formErrorsHelper.config.formErrorMessageClass)).remove();
    })
};

app.walletSendMoney.ajax = function() {
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

app.walletSendMoney.checkout = function() {
    const data = this.config.data;
    if (data) {
        const message = `<ul class="list-group mb-4">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Amount')}</strong>
                                <h3 class="mb-0">
                                    ${data.amount_display}
                                </h3>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Receiver')}</strong>

                                <div class="d-flex flex-column align-items-end">
                                    <strong>${data.recepient.full_name}</strong>
                                    <span>${data.recepient.email}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Total Payable')}</strong>
                                <h3 class="text-success mb-0">
                                    ${data.total_display}
                                </h3>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <strong>${app.trans('Charges')}</strong>

                                <strong class="d-flex align-items-center text-danger">
                                    <span class="wallet-exchange-fixed-amount">${data.fixed_charge_display}</span>
                                    <span class="mx-1">+</span>
                                    <span class="wallet-exchange-rate">${data.rate_charge_display}</span>
                                </strong>
                            </div>
                        </li>
                    </ul>`;

        const dialog = bootbox.dialog({
            title: app.trans('Send money details'),
            message: message,
            buttons: {
                cancel: {
                    label: app.trans("Cancel"),
                    className: 'btn-danger'
                },
                ok: {
                    label: app.trans("Send now"),
                    className: 'btn-primary',
                    callback: function(){
                        var convertingDialog = bootbox.dialog({
                            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Processing')+'...</p>',
                            closeButton: false
                        });

                        if (window.allow_send_money_otp) {
                            app.walletOtp.confirm({
                                success: function(response) {
                                    const data = app.walletSendMoney.config.data;
                                    app.walletSendMoney.config.data = null;
                                    $.ajax({
                                        type: 'POST',
                                        url: app.walletSendMoney.config.checkoutRoute,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                        success: function (response, textStatus, xhr) {
                                            app.walletSendMoney.config.data = null;
                                            setTimeout(function() {
                                                app.notify(response.message);
                                                app.redirect(response.data.redirectTo);
                                            }, 350);
                                        },
                                        error: function (response, textStatus, errorThrown) {
                                            app.walletSendMoney.config.data = data;
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
                            }, 'wallet_send_money_checkout');
                        } else {
                            var $button = $('.btn-send-money-show-modal');
                            var $content = $button.html();
                            const data = app.walletSendMoney.config.data;
                            app.walletSendMoney.config.data = null;
                            $.ajax({
                                type: 'POST',
                                url: app.walletSendMoney.config.checkoutRoute,
                                contentType: false,
                                cache: false,
                                processData: false,
                                beforeSend: function() {
                                    app.buttonLoader($button);
                                },
                                success: function (response, textStatus, xhr) {
                                    app.backButtonContent($button, $content);
                                    app.walletSendMoney.config.data = null;
                                    setTimeout(function() {
                                        app.notify(response.message);
                                        app.redirect(response.data.redirectTo);
                                    }, 350);
                                },
                                error: function (response, textStatus, errorThrown) {
                                    app.backButtonContent($button, $content);
                                    app.walletSendMoney.config.data = data;
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

app.walletSendMoney.onOtpCancel = function() {
    $(app.walletOtp.config.modal).on('hide.bs.modal', function (event) {
        setTimeout(function() {
            app.walletSendMoney.checkout();
        }, 350);
    })
};

app.walletSendMoney.init = function() {
    this.onModalShow();
    this.ajax();

    this.onOtpCancel();
};

$(document).ready(app.walletSendMoney.init());
