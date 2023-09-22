"use strict";

// define app.enableProfileTwoFactor object
app.enableProfileTwoFactor = {};

// handle app.enableProfileTwoFactor object configuration
app.enableProfileTwoFactor.config = {
    form: '#profile-two-factor-form',
    route: '/profile/two-factor/enable'
};

// handle getting app.enableProfileTwoFactor object form data
app.enableProfileTwoFactor.data = function() {
    return $(this.config.form).serializeArray();
};

// handle updating content for .2fa-content element
app.enableProfileTwoFactor.updateContent = function(content) {
    $('.2fa-content').html(content);
};

// handle ajax request for enabling two factor
app.enableProfileTwoFactor.process = function(e) {
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
            button.attr('disabled', false);
            $('#2fa-download-recovery-codes-modal').modal('show');
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            button.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    }); 
};

// initialize functions of app.enableProfileTwoFactor object
app.enableProfileTwoFactor.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.enableProfileTwoFactor object until the document is loaded
$(document).ready(app.enableProfileTwoFactor.init());