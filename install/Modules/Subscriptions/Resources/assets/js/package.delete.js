app.deletePackage = {};

app.deletePackage.config = {
    button: '.btn-delete-package'
};

app.deletePackage.ajax = function(route) {
    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove package."),
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
                var dialogRemovePackages = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+ app.trans('Removing resources') + '...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'POST',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        setTimeout(function() {
                            app.notify(response.message);
                            dialogRemovePackages.modal('hide');
                            if (app.packageDatatable.datatable) {
                                app.packageDatatable.datatable.refresh();
                            } else {
                                if (response.data.redirectTo) {
                                    app.redirect(response.data.redirectTo);
                                }
                            }
                        }, 250);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        // Check check if form validation errors
                        setTimeout(function() {
                            dialogRemovePackages.modal('hide');
                            bootbox.alert(response.responseJSON.message);
                        }, 500);
                    }
                });
            }
        }
    });
};

app.deletePackage.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', function() {
        var route = $(this).data('href');
        self.ajax(route);
    });
};

$(document).ready(app.deletePackage.init());
