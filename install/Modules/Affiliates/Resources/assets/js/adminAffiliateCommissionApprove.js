"use strict";

app.adminAffiliateCommissionApprove = {};

app.adminAffiliateCommissionApprove.config = {
    button: '.btn-admin-commission-approve'
};

app.adminAffiliateCommissionApprove.ajax = function() {
    const self = this;
    $(document).delegate(self.config.button, 'click', function(e) {
        const $button = $(this).closest('.dropdown-menu').prev();
        const buttonContent = $button.html();
        const name = $(this).data('name') || $(this).data('email');
        const route = $(this).data('route');

        bootbox.confirm({
            title: app.trans("Approve commission?"),
            message: app.trans("Do you want to approve :name commission?", {name: name}),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> '+app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> '+app.trans('Approve now')
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
                                app.adminAffiliateCommissionDatatable.table.refresh();
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

app.adminAffiliateCommissionApprove.init = function() {
    this.ajax();
};

$(document).ready(app.adminAffiliateCommissionApprove.init());
