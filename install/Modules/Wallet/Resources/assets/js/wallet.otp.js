"use strict";

app.walletOtp = {};

app.walletOtp.config = {
    modal: '#wallet-confirm-otp-modal',
    form: '#wallet-confirm-otp-form',
    send: '/profile/wallet/send-otp',
    resend: '/profile/wallet/resend-otp',
    verify: '/profile/wallet/verify-otp',
    btn: {
        verify: '#btn-confirm-otp',
        resend: '#btn-resend-otp'
    },
    options: {
        before: function() {},
        success: function() {},
        error: function() {},
        always: function() {}
    },
    resource: null
};

app.walletOtp.confirm = function(options, resource = null) {
    const self = this;
    self.config.resource = resource;
    self.config.options = $.extend({}, this.config.options, options);

    $.ajax({
        type: "GET",
        url: self.config.send,
        beforeSend: function() {
            self.config.options.before();
        }
    })
    .done(function(json) {
        setTimeout(function() {
            $(self.config.modal).find('[name="otp"]').val('');
            $(self.config.modal).modal('show');
            self.config.options.always();
        }, 350);
    })
    .fail(function(response) {
        setTimeout(function() {
            self.config.options.error(response);
            self.config.options.always();
        }, 350);
    });
};

app.walletOtp.resend = function() {
    const self = this;
    $(self.config.btn.resend).on('click', function() {
        $.ajax({
            type: "GET",
            url: self.config.resend,
            beforeSend: function() {
                $(self.config.btn.resend).attr('disabled', true).addClass('disabled btn-progress');
            },
            success: function() {
                setTimeout(function() {
                    $(self.config.btn.resend).attr('disabled', false).removeClass('disabled btn-progress');
                }, 350);
            },
            error: function() {
                setTimeout(function() {
                    $(self.config.btn.resend).attr('disabled', false).removeClass('disabled btn-progress');
                }, 350);
            }
        })
    });
};

app.walletOtp.verify = function() {
    const self = this;
    $(self.config.btn.verify).on('click', function() {
        const otp = $(self.config.form).find('[name="otp"]').val();

        $.ajax({
            type: "POST",
            url: self.config.verify,
            data: {otp, otp_checkout_resource: self.config.resource},
            beforeSend: function() {
                $(self.config.btn.verify).attr('disabled', true).addClass('disabled btn-progress');
            },
            success: function(response) {
                setTimeout(function() {
                    $(self.config.btn.verify).attr('disabled', false).removeClass('disabled btn-progress');
                    self.config.options.success(response);
                    $(self.config.modal).modal('hide');
                }, 350);
            },
            error: function(response) {
                setTimeout(function() {
                    $(self.config.btn.verify).attr('disabled', false).removeClass('disabled btn-progress');
                    self.config.options.error(response);
                }, 350);
            }
        });
    });
};

app.walletOtp.init = function() {
    const self = this;
    $(this.config.modal).on('hidden.bs.modal', function () {
        $(this).find('[name="otp"]').val('');
        self.config.options.always();
    });

    self.verify();
    self.resend();
};

$(document).ready(app.walletOtp.init());
