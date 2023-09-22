"use strict";

// define app.updateProfileLanguage object
app.updateProfileLanguage = {};

// handle app.updateProfileLanguage object configuration
app.updateProfileLanguage.config = {
    form: '#profile-language-form',
    route: '/profile/language'
};

// handle getting app.updateProfileLanguage object form data
app.updateProfileLanguage.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.updateProfileLanguage object ajax request
app.updateProfileLanguage.process = function(e) {
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
            app.redirect('/profile');
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

// initialize functions of app.updateProfileLanguage object
app.updateProfileLanguage.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.updateProfileLanguage object until the document is loaded
$(document).ready(app.updateProfileLanguage.init());