"use strict";

// define verify2FA object
app.verify2FA = {};

// handle verify2FA object configuration
app.verify2FA.config = {
	form: '#2fa-login-verify-form',
	route: '/verify/two-factor'
};

// handle verify2FA object form data
app.verify2FA.data = function() {
	return $(this.config.form).serializeArray();
};

// handle verify2FA object ajax request
app.verify2FA.process = function(e) {
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
        	var response = XMLHttpRequest;
        	// Check check if form validation errors
        	app.formErrors($self.config.form, response.responseJSON, response.status);
        }
    });	
};

// initialize verify2FA object functions
app.verify2FA.init = function() {
	$(this.config.form).on('submit', this.process.bind(this));
};

// wait until the document is loaded
$(document).ready(app.verify2FA.init());