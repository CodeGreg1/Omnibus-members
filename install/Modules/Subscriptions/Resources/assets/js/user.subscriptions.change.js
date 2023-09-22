"use strict";

app.userSubscriptionChange = {};

app.userSubscriptionChange.config = {
    button: '#btn-submit-subscription-change'
};

app.userSubscriptionChange.ajax = function(route) {
    var dialogUserSubscriptionChange = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Changing subscription')+'...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'GET',
        url: route,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            }, 500);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            setTimeout(function() {
                dialogUserSubscriptionChange.modal('hide');
                bootbox.alert(response.responseJSON.message);
            }, 500);
        }
    });
}

app.userSubscriptionChange.process = function(e) {
    var self = this;
    var route = $(e.target).data('href');
    var button = $(e.target);
    bootbox.confirm({
        title: app.trans("Are you sure to change the package?"),
        message: app.trans("Are you sure you want to change your package? Your previous subscription will be cancelled."),
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> ' + app.trans('Cancel')
            },
            confirm: {
                label: '<i class="fa fa-check"></i> ' + app.trans('Confirm')
            }
        },
        callback: function (result) {
            if (result) {
                $.ajax({
                    type: 'GET',
                    url: route,
                     beforeSend: function () {
                        button.attr('disabled', true).addClass('loading disabled');
                    },
                    success: function (response, textStatus, xhr) {
                        button.attr('disabled', false).removeClass('loading disabled');
                        setTimeout(function() {
                            app.notify(response.message);
                            if (response.data.redirectTo) {
                                app.redirect(response.data.redirectTo);
                            }
                        }, 500);
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        var response = XMLHttpRequest;
                        button.attr('disabled', false).removeClass('loading disabled');
                        setTimeout(function() {
                            bootbox.alert(response.responseJSON.message);
                        }, 500);
                    }
                });
            }
        }
    });
};

app.userSubscriptionChange.init = function() {
    var self = this;
    $(document).delegate(self.config.button, 'click', self.process.bind(this));
}

$(document).ready(app.userSubscriptionChange.init());
