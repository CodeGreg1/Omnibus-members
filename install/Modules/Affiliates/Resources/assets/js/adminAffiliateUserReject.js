"use strict";

app.adminAffiliateUserReject = {};

app.adminAffiliateUserApprove.config = {
    button: '.btn-admin-affiliate-reject'
};

app.adminAffiliateUserApprove.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this).closest('.dropdown-menu').prev();
        const buttonContent = $button.html();
        const user = $(this).data('user');
        const route = $(this).data('route');

        bootbox.prompt({
            title: app.trans("Do you want to reject affiliate request of :user?", {user}),
            inputType: 'textarea',
            callback: function (result) {
                if (result !== null) {
                    $.ajax({
                        type: 'PUT',
                        url: route,
                        data: {reason: result},
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            setTimeout(() => {
                                app.notify(response.message);
                                app.adminAffiliateUserDatatable.table.refresh();
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

app.adminAffiliateUserApprove.init = function() {
    this.ajax();
};

$(document).ready(app.adminAffiliateUserApprove.init());
