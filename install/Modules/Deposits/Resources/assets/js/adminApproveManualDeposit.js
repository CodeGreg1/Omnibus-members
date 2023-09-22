"use strict";

app.adminApproveManualDeposit = {};

app.adminApproveManualDeposit.config = {
    button: '#btn-approve-manual-deposit'
};

app.adminApproveManualDeposit.ajax = function() {
    const self = this;
    $(self.config.button).on('click', function(e) {
        const $button = $(this);
        const route = $button.data('route');
        const buttonContent = $button.html();

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to approve this deposit request!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-primary'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                    $.ajax({
                        type: 'POST',
                        url: route,
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

app.adminApproveManualDeposit.init = function() {
    this.ajax();
};

$(document).ready(app.adminApproveManualDeposit.init());
