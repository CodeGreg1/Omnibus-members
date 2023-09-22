app.deletePackageFeature = {};

app.deletePackageFeature.config = {
    btn: '.btn-delete-feature'
};

app.deletePackageFeature.process = function(e) {
    var route = $(e.target).data('route');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove feature."),
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
                var dialogRemovePackagePriceGateway = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        // if (response.data.redirectTo) {
                        //     app.redirect(response.data.redirectTo);
                        // }
                        app.packageFeatureDatatable.tb.refresh();
                        setTimeout(function() {
                            dialogRemovePackagePriceGateway.modal('hide');
                            app.notify(response.message);
                        }, 350);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemovePackagePriceGateway.modal('hide');
                            app.notify(response.responseJSON.message);
                        }, 350);
                    }
                });
            }
        }
    });
};

app.deletePackageFeature.init = function() {
    $(document).delegate(this.config.btn, 'click', this.process.bind(this));
}

app.deletePackageFeature.init();
