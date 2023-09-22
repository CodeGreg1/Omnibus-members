"use strict";

// define login object
app.login = {};

// handle login object configuration
app.login.config = {
	form: '#login-form',
	route: '/login'
};

// handle getting login object form data
app.login.data = function() {
	return $(this.config.form).serializeArray();
};

// handle login object ajax request
app.login.process = function(e) {
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
        	app.formErrors('#login-form', response.responseJSON, response.status);
        }
    });	
};

// initialize functions of app.login object
app.login.init = function() {
	$(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.login object until the document is loaded
$(document).ready(app.login.init());