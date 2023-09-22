app.deleteCouponPackage = {};

app.deleteCouponPackage.config = {
    button: '.btn-delete-coupon-package'
};

app.deleteCouponPackage.ajax = function(e) {
    var route = $(e.target).data('href');

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to remove package from list."),
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
                var dialogRemoveCouponPackageGateway = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: route,
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        if (response.data.redirectTo) {
                            app.redirect(response.data.redirectTo);
                        }
                        setTimeout(function() {
                            dialogRemoveCouponPackageGateway.modal('hide');
                        }, 350);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        setTimeout(function() {
                            dialogRemoveCouponPackageGateway.modal('hide');
                            app.notify(response.responseJSON.message);
                        }, 350);
                    }
                });
            }
        }
    });
};

app.deleteCouponPackage.init = function() {
    $(document).delegate(this.config.button, 'click', this.ajax.bind(this));
};

$(document).ready(app.deleteCouponPackage.init());
