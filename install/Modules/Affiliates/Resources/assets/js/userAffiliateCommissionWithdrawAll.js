"use strict";

app.userAffiliateCommissionWithdrawAll = {};

app.userAffiliateCommissionWithdrawAll.config = {
    button: '.btn-commission-withdraw-all',
    route: '/user/commissions/withdraw-all',
    redirectTo: '/user/affiliates'
};

app.userAffiliateCommissionWithdrawAll.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this);
        const buttonContent = $button.html();

        bootbox.confirm({
            title: app.trans("Withdraw all?"),
            message: app.trans("To withdraw all available commissions, kindly move commission to your wallet. Do you want to continue?"),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> '+app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> '+app.trans('Move all now')
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'GET',
                        url: self.config.route,
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            setTimeout(() => {
                                app.notify(response.message);
                                app.redirect(response.data.redirectTo);
                            }, 350);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            // Check check if form validation errors
                            setTimeout(() => {
                                app.notify(response.responseJSON.message);
                                app.backButtonContent($button, buttonContent);
                            }, 350);
                        }
                    });
                }
            }
        });
    });
};

app.userAffiliateCommissionWithdrawAll.init = function() {
    this.ajax();
};

$(document).ready(app.userAffiliateCommissionWithdrawAll.init());
