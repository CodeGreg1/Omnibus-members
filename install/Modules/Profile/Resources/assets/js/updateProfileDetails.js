"use strict";

// define app.updateProfileDetails object
app.updateProfileDetails = {};

// handle app.updateProfileDetails object configuration
app.updateProfileDetails.config = {
    form: '#profile-details-form',
    route: '/profile/details'
};

// handle getting app.updateProfileDetails object form data
app.updateProfileDetails.data = function() {
    return $(this.config.form).serializeArray();
};

// handle getting app.updateProfileDetails object updating view
app.updateProfileDetails.updateView = function() {
    $('.profile-first-name').text($('[name="first_name"]').val());
    $('.profile-last-name').text($('[name="last_name"]').val());
};

// handle app.updateProfileDetails object ajax request
app.updateProfileDetails.process = function(e) {
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

// initialize functions of app.updateProfileDetails object
app.updateProfileDetails.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateProfileDetails object until the document is loaded
$(document).ready(app.updateProfileDetails.init());