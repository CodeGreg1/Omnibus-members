"use strict";

// define app.usersUpdateSettings object
app.usersUpdateSettings = {};

// handle app.usersUpdateSettings object configuration
app.usersUpdateSettings.config = {
    form: '#user-settings-form'
};

// handle getting app.usersUpdateSettings object form data
app.usersUpdateSettings.data = function() {
    return $(this.config.form).serializeArray();
};

// handle on getting route for updating user settings
app.usersUpdateSettings.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.usersUpdateSettings object ajax request
app.usersUpdateSettings.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: app.usersUpdateSettings.route(),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.usersUpdateSettings object
app.usersUpdateSettings.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.usersUpdateSettings object until the document is loaded
$(document).ready(app.usersUpdateSettings.init());