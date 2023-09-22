"use strict";

app.adminDatabaseBackupCreate = {};

app.adminDatabaseBackupCreate.config = {
    route: '/admin/database-backup/create',
    btn: '#btn-admin-backup-database'
};

app.adminDatabaseBackupCreate.ajax = function(e) {
    var $self = this;

     $(app.adminDatabaseBackupCreate.config.btn).on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: app.adminDatabaseBackupCreate.config.route,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                app.notify(response.message);
                app.adminDatabaseBackupDatatable.tb.refresh();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.notify(response.responseJSON.message);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminDatabaseBackupCreate.init = function() {
    app.adminDatabaseBackupCreate.ajax();
};

$(document).ready(app.adminDatabaseBackupCreate.init());
