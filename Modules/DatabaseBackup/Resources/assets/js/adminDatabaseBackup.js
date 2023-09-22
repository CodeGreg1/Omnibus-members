"use strict";

app.adminDatabaseBackup = {};

app.adminDatabaseBackup.init = function() {
    $('[name="database_auto_backup"]').on('change', function() {
        if (this.checked) {
            $('.database-backup-frequency').removeClass('d-none');
        } else {
            $('.database-backup-frequency').addClass('d-none');
        }
    });
};

$(document).ready(app.adminDatabaseBackup.init());
