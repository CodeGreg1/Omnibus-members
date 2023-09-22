app.resumeSubscription = {};

app.resumeSubscription.config = {
    button: '.btn-resume-subscription'
};

app.resumeSubscription.ajax = function(route, data) {
    var dialogSubscriptionCancel = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Resuming subscription')+'...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'POST',
        url: route,
        data: data,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
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
            }, 500);
        }
    });
}

app.resumeSubscription.process = function(e) {
    var self = this;
    var id = $(e.target).data('id'), subscription;
    if (app.subscriptionDatatable.datatable) {
        var subscription = app.subscriptionDatatable.datatable.findItem('id', parseInt(id));
    } else {
        var subscription = window.subscription;
    }
    if (subscription) {
        var route = '/admin/subscriptions/'+subscription.id+'/resume';
        bootbox.prompt({
            title: "Please enter your password to confirm action",
            inputType: 'password',
            callback: function (result) {
                if (result) {
                    self.ajax(route, {
                        password: result
                    });
                }
            }
        });
    }
};

app.resumeSubscription.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', self.process.bind(this));
}

$(document).ready(app.resumeSubscription.init());
