"use strict";

app.removePackageFeature = {};

app.removePackageFeature.config = {
    button: '.btn-remove-package-feature'
};

app.removePackageFeature.process = function(e) {
    const self = this;
    const $btn = $(e.target).closest('button');
    const route = $btn.data('route');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove a feature from package."),
        buttons: {
            confirm: {
                label: app.trans('Yes'),
                className: 'btn-danger'
            },
            cancel: {
                label: app.trans('No'),
                className: 'btn-default'
            }
        },
        callback: function (result) {
            if ( result ) {

                var dialogRemovePackageFeatureGateway = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        if (response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                        setTimeout(function() {
                            dialogRemovePackageFeatureGateway.modal('hide');
                            app.notify(response.message);
                        }, 350);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemovePackageFeatureGateway.modal('hide');
                            app.notify(response.responseJSON.message);
                        }, 350);
                    }
                });
             }
        }
    });
};

app.removePackageFeature.init = function() {
    $(document).delegate(app.removePackageFeature.config.button, 'click', this.process.bind(this));
};

$(document).ready(app.removePackageFeature.init());
