app.deletePackagePrice = {};

app.deletePackagePrice.config = {
    button: '.btn-delete-package-price'
};

app.deletePackagePrice.ajax = function(route) {
    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove price."),
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
                var dialogRemovePackagesGateway = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+ app.trans('Removing resources') + '...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        dialogRemovePackagesGateway.modal('hide');
                        if (response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        // Check check if form validation errors
                        setTimeout(function() {
                            app.notify(response.responseJSON.message);
                            dialogRemovePackagesGateway.modal('hide');
                        }, 500);
                    }
                });
            }
        }
    });
};

app.deletePackagePrice.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', function() {
        var route = $(this).data('route');
        self.ajax(route);
    });
};

$(document).ready(app.deletePackagePrice.init());
