app.cancelSubscription = {};

app.cancelSubscription.config = {
    button: '.btn-cancel-subscription'
};

app.cancelSubscription.ajax = function(route) {
    var dialogSubscriptionCancel = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Cancelling subscription')+'...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'GET',
        url: route,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                dialogSubscriptionCancel.modal('hide');
                if (app.subscriptionDatatable.datatable) {
                    app.subscriptionDatatable.datatable.refresh();
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
                dialogSubscriptionCancel.modal('hide');
                bootbox.alert(response.responseJSON.message);
            }, 500);
        }
    });
}

app.cancelSubscription.process = function(e) {
    var self = this;
    var id = $(e.target).data('id');
    app.closeModal();
    if (id) {
        bootbox.confirm({
            title: app.trans('Cancel subscription?'),
            message: app.trans('Do you want to cancel your subscription? Once canceled cannot be reverted.'),
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
                    const route = '/admin/subscriptions/'+id+'/cancel';
                    self.ajax(route);
                }
            }
        });
    }
};

app.cancelSubscription.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', self.process.bind(this));
}

$(document).ready(app.cancelSubscription.init());
