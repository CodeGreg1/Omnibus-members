"use strict";

// define app.modulesRunMigrations object
app.modulesRunMigrations = {};

// handle app.modulesRunMigrations object configuration
app.modulesRunMigrations.config = {
    migrationCheck: '/admin/module/migration-check',
    migrationRun: '/admin/module/migration-run',
    elementMessageId: '#modules-message',
};

// handle on migration check with ajax request to check to the server module migration status
app.modulesRunMigrations.migrationCheck = function() {
    
    if($(app.modulesRunMigrations.config.elementMessageId).length) {
       var self = this;

        $.ajax({
            type: 'GET',
            url: self.config.migrationCheck,
            success: function(total) {
                if(total > 0) {
                    app.modulesRunMigrations.migrationRun();
                }
            }
        }); 
    }
};

// handle on running migration with ajax request
app.modulesRunMigrations.migrationRun = function() {
    var self = this;

    $.ajax({
        type: 'GET',
        url: self.config.migrationRun,
        beforeSend: function() {
            app.modulesRunMigrations.showMessage(app.trans('Please wait currently running migration.'));
        },
        success: function(response) {
            app.modulesRunMigrations.hideMessage();
        }
    });
};

// handle on hiding message
app.modulesRunMigrations.hideMessage = function() {
    $(app.modulesRunMigrations.config.elementMessageId).html('');
    $(app.modulesRunMigrations.config.elementMessageId).hide();
};

// handle on showing message
app.modulesRunMigrations.showMessage = function(message) {
    $(app.modulesRunMigrations.config.elementMessageId).html('<i class=\"fas fa-spinner fa-spin\"></i> ' + message);
    $(app.modulesRunMigrations.config.elementMessageId).show();
};

// initialize migrationCheck() function
app.modulesRunMigrations.init = function() {
    app.modulesRunMigrations.migrationCheck();
};

// initialize app.modulesRunMigrations object until the document is loaded
$(document).ready(app.modulesRunMigrations.init());