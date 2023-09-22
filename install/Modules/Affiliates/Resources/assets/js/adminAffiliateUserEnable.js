"use strict";

app.adminAffiliateUserEnable = {};

app.adminAffiliateUserEnable.config = {
    button: '.btn-admin-affiliate-enable'
};

app.adminAffiliateUserEnable.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this).closest('.dropdown-menu').prev();
        const buttonContent = $button.html();
        const user = $(this).data('user');
        const route = $(this).data('route');

        bootbox.confirm({
            title: app.trans("Enable affiliate?"),
            message: app.trans("Do you want to enable affiliate membership of :user?", {user}),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> ' + app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> ' + app.trans('Confirm')
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'PUT',
                        url: route,
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

app.adminAffiliateUserEnable.init = function() {
    this.ajax();
};

$(document).ready(app.adminAffiliateUserEnable.init());
