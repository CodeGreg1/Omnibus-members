"use strict";

app.adminRejectManualDeposit = {};

app.adminRejectManualDeposit.config = {
    button: '#btn-reject-manual-deposit'
};

app.adminRejectManualDeposit.ajax = function() {
    const self = this;
    $(self.config.button).on('click', function(e) {
        const $button = $(this);
        const route = $button.data('route');
        const buttonContent = $button.html();

        bootbox.prompt({
            title: app.trans("Are you sure you want to reject this request?"),
            message: '<p class="mb-1">'+app.trans('Reason')+'<span class="text-muted"> ('+app.trans('Optional')+')</span></p>',
            inputType: 'textarea',
            callback: function (result) {
                if (result !== null) {
                     $.ajax({
                        type: 'POST',
                        url: route,
                        data: {reason: result},
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

app.adminRejectManualDeposit.init = function() {
    this.ajax();
};

$(document).ready(app.adminRejectManualDeposit.init());
