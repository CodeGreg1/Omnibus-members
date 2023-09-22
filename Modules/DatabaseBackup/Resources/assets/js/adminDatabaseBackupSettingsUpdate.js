"use strict";

app.adminDatabaseBackupSettingsUpdate = {};

app.adminDatabaseBackupSettingsUpdate.config = {
    form: '#admin-database-backup-settings-update-form',
    route: '/admin/database-backup/settings/update'
};

app.adminDatabaseBackupSettingsUpdate.ajax = function(e) {
    var $self = this;

     $(app.adminDatabaseBackupSettingsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminDatabaseBackupSettingsUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminDatabaseBackupSettingsUpdate.config.route,
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
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminDatabaseBackupSettingsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminDatabaseBackupSettingsUpdate.init = function() {
    app.adminDatabaseBackupSettingsUpdate.ajax();
};

$(document).ready(app.adminDatabaseBackupSettingsUpdate.init());
