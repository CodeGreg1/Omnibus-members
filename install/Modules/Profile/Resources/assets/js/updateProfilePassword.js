"use strict";

// define app.updateProfilePassword object
app.updateProfilePassword = {};

// handle app.updateProfilePassword object configuration
app.updateProfilePassword.config = {
    form: '#profile-password-form',
    route: '/profile/password'
};

// handle getting app.updateProfilePassword object form data
app.updateProfilePassword.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateProfilePassword object ajax request
app.updateProfilePassword.process = function(e) {
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

            if(response.data.redirectTo) {
                setTimeout(function() {
                    app.redirect(response.data.redirectTo);
                }, 1000);
            }
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

// initialize functions of app.updateProfilePassword object
app.updateProfilePassword.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateProfilePassword object until the document is loaded
$(document).ready(app.updateProfilePassword.init());