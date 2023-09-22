"use strict";

// define app.updateProfileCurrency object
app.updateProfileCurrency = {};

// handle app.updateProfileCurrency object configuration
app.updateProfileCurrency.config = {
    form: '#profile-currency-form',
    route: '/profile/currency'
};

// handle getting app.updateProfileCurrency object form data
app.updateProfileCurrency.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateProfileCurrency object ajax request
app.updateProfileCurrency.process = function(e) {
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

// initialize functions of app.updateProfileCurrency object
app.updateProfileCurrency.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateProfileCurrency object until the document is loaded
$(document).ready(app.updateProfileCurrency.init());