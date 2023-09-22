"use strict";

app.adminAffiliateCommissionTypeDisable = {};

app.adminAffiliateCommissionTypeDisable.config = {
    button: '.btn-admin-affiliate-setting-disable'
};

app.adminAffiliateCommissionTypeDisable.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this).closest('.dropdown-menu').prev();
        const buttonContent = $button.html();
        const name = $(this).data('name');
        const route = $(this).data('route');

        bootbox.confirm({
            title: app.trans("Disable commission type?"),
            message: app.trans("Do you want to disable :name type?", {name: name}),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> '+app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> '+app.trans('Disable')
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

app.adminAffiliateCommissionTypeDisable.init = function() {
    this.ajax();
};

$(document).ready(app.adminAffiliateCommissionTypeDisable.init());
