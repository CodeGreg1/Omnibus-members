"use strict";

// define app.verifyEnableProfileTwoFacto object
app.verifyEnableProfileTwoFactor = {};

// handle app.verifyEnableProfileTwoFactor object configuration
app.verifyEnableProfileTwoFactor.config = {
    form: '#profile-verify-two-factor-form',
    route: '/profile/two-factor/verify-enable',
    redirect: '/profile/security'
};

// handle getting app.verifyEnableProfileTwoFactor object form data
app.verifyEnableProfileTwoFactor.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.verifyEnableProfileTwoFactor object ajax request
app.verifyEnableProfileTwoFactor.process = function(e) {
    e.preventDefault();

    var self = this;
    var button = $(self.config.form).find(':submit');
    var content = button.html();

    $.ajax({
        type: 'POST',
        url: self.config.route,
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(self.config.redirect);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
            app.backButtonContent(button, content);
            button.attr('disabled', false);
        }
    }); 
};

// initialize functions of app.verifyEnableProfileTwoFactor object
app.verifyEnableProfileTwoFactor.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.verifyEnableProfileTwoFactor object until the document is loaded
$(document).ready(app.verifyEnableProfileTwoFactor.init());