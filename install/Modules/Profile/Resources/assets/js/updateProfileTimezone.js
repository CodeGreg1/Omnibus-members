"use strict";

// define app.updateProfileTimezone object
app.updateProfileTimezone = {};

// handle app.updateProfileTimezone object configuration
app.updateProfileTimezone.config = {
    form: '#profile-timezone-form',
    route: '/profile/timezone'
};

// handle getting app.updateProfileTimezone object form data
app.updateProfileTimezone.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateProfileTimezone object ajax request
app.updateProfileTimezone.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: $self.config.route,
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
        },
        complete: function (xhr) {
            app.backButtonContent($button, $content);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
        }
    }); 
};

// initialize functions of app.updateProfileTimezone object
app.updateProfileTimezone.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateProfileTimezone object until the document is loaded
$(document).ready(app.updateProfileTimezone.init());