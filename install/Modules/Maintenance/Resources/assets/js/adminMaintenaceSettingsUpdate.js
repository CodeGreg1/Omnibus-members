"use strict";

app.adminMaintenaceSettingsUpdate = {};

app.adminMaintenaceSettingsUpdate.config = {
    form: '#maintenance-settings-form',
    route: '/admin/maintenance/settings/update'
};

app.adminMaintenaceSettingsUpdate.ajax = function(e) {
    var $self = this;

     $(app.adminMaintenaceSettingsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminMaintenaceSettingsUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminMaintenaceSettingsUpdate.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                app.notify(response.message);
                setTimeout(function() {
                    window.location = window.location.href;
                }, 2000);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminMaintenaceSettingsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminMaintenaceSettingsUpdate.init = function() {
    app.adminMaintenaceSettingsUpdate.ajax();
};

$(document).ready(app.adminMaintenaceSettingsUpdate.init());
