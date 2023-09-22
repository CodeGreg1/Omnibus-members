"use strict";

// define app.updateCompanyProfile object
app.updateCompanyProfile = {};

// handle app.updateCompanyProfile object configuration
app.updateCompanyProfile.config = {
    form: '#profile-company-form',
    route: '/profile/company'
};

// handle getting app.updateCompanyProfile object form data
app.updateCompanyProfile.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateCompanyProfile object ajax request
app.updateCompanyProfile.process = function(e) {
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
            app.updateProfileDetails.updateView();
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

// initialize functions of app.updateCompanyProfile object
app.updateCompanyProfile.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateCompanyProfile object until the document is loaded
$(document).ready(app.updateCompanyProfile.init());