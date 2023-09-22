"use strict";

app.userAffiliateCommissionWithdraw = {};

app.userAffiliateCommissionWithdraw.config = {
    button: '.btn-commission-withdraw'
};

app.userAffiliateCommissionWithdraw.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this);
        const buttonContent = $button.html();
        const route = $(this).data('route');

        bootbox.confirm({
            title: app.trans("Withdraw commission?"),
            message: app.trans("To withdraw, kindly move commission to your wallet. Do you want to continue?"),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> '+app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> '+app.trans('Move now')
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'GET',
                        url: route,
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            setTimeout(() => {
                                app.notify(response.message);
                                app.userAffiliateCommissionDatatable.table.refresh();
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

app.userAffiliateCommissionWithdraw.init = function() {
    this.ajax();
};

$(document).ready(app.userAffiliateCommissionWithdraw.init());
