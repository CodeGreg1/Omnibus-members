"use strict";

// define app.updateAddressProfile object
app.updateAddressProfile = {};

// handle app.updateAddressProfile object configuration
app.updateAddressProfile.config = {
    form: '#profile-address-form',
    route: '/profile/address'
};

// handle getting app.updateAddressProfile object form data
app.updateAddressProfile.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateAddressProfile object ajax request
app.updateAddressProfile.process = function(e) {
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

// initialize functions of app.updateAddressProfile object
app.updateAddressProfile.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateAddressProfile object until the document is loaded
$(document).ready(app.updateAddressProfile.init());