"use strict";

// Define forgot password object
app.forgotPassword = {};

// Handle forgot password configuration
app.forgotPassword.config = {
	form: '#forgot-password-form',
	route: '/password/email'
};

// Handle getting form data
app.forgotPassword.data = function() {
	return $(this.config.form).serializeArray();
};

// Handle form ajax request
app.forgotPassword.process = function(e) {
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
            app.formMessage($self.config.form, response.message);

            setTimeout(function() {
                if(response.data.redirectTo !== undefined) {
                    app.redirect(response.data.redirectTo);
                }
            }, 2000);
        },
        complete: function (xhr) {
            app.backButtonContent($button, $content);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	// Check check if form validation errors
        	app.formErrors($self.config.form, XMLHttpRequest.responseJSON, XMLHttpRequest.status);
        }
    });	
};

// Initialize forgot password functions
app.forgotPassword.init = function() {
	$(this.config.form).on('submit', this.process.bind(this));
};

// wait until the document is loaded
$(document).ready(app.forgotPassword.init());