"use strict";

// define resetPassword object
app.resetPassword = {};

// handle resetPassword configuration
app.resetPassword.config = {
	form: '#reset-password-form',
	route: '/password/reset'
};

// handle resetPassword form data
app.resetPassword.data = function() {
	return $(this.config.form).serializeArray();
};

// handle resetPassword object ajax request
app.resetPassword.process = function(e) {
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
            if(response.data.redirectTo !== undefined) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function (xhr) {
            if(xhr.status != 200) {
                app.backButtonContent($button, $content);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	// Check check if form validation errors
        	app.formErrors($self.config.form, XMLHttpRequest.responseJSON, XMLHttpRequest.status);
        }
    });	
};

// initialize resetPassword object functions
app.resetPassword.init = function() {
	$(this.config.form).on('submit', this.process.bind(this));
};

// wait until the document is loaded
$(document).ready(app.resetPassword.init());