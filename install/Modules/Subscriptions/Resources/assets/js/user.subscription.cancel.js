app.userSubscriptionCancel = {};

app.userSubscriptionCancel.config = {
    button: '.btn-cancel-user-subscription'
};

app.userSubscriptionCancel.ajax = function(route) {
    var dialogUserSubscriptionCancel = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Cancelling subscription')+'...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'GET',
        url: route,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                dialogUserSubscriptionCancel.modal('hide');
                if (app.userSubscriptionDatatable.datatable) {
                    app.userSubscriptionDatatable.datatable.refresh();
                } else {
                    if (response.data.redirectTo) {
                        app.redirect(response.data.redirectTo);
                    }
                }

            }, 500);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            setTimeout(function() {
                dialogUserSubscriptionCancel.modal('hide');
                bootbox.alert(response.responseJSON.message);
            }, 500);
        }
    });
}

app.userSubscriptionCancel.process = function(e) {
    var self = this;
    var id = $(e.target).data('id');
    bootbox.confirm({
        title: "Cancel subscription?",
        message: "Do you want to cancel your subscription? Once canceled cannot be reverted.",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result) {
                const route = '/user/subscriptions/'+id+'/cancel';
                self.ajax(route);
                // var mode = result;
                // var route = '/user/subscriptions/'+id+'/cancel';
                // bootbox.prompt({
                //     title: "Please enter your password to confirm action",
                //     inputType: 'password',
                //     callback: function (result) {
                //         if (result) {
                //             self.ajax(route, {
                //                 password: result,
                //                 mode
                //             });
                //         }
                //     }
                // });
            }
        }
    });
};

app.userSubscriptionCancel.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', self.process.bind(this));
}

$(document).ready(app.userSubscriptionCancel.init());
