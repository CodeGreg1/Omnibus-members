"use strict";

// define register object
app.register = {};

// handle register configuration
app.register.config = {
	form: '#register-form',
	route: '/register'
};

// handle register form data
app.register.data = function() {
	return $(this.config.form).serializeArray();
};

// handle accepting terms and condition
app.register.acceptTos = function() 
{
    $('#terms-and-condition-modal #accept-tos-button').on('click', function() {
        $('#register-form [name="terms"]').prop('checked', true);
        app.closeModal();
    });
}

// handle register ajax request
app.register.process = function(e) {
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
            if(response.data.hasOwnProperty('redirectTo')) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function(xhr) {
            if(xhr.status != 200 || xhr.status != 201) {
                app.backButtonContent($button, $content);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	var response = XMLHttpRequest;
        	// Check check if form validation errors
        	app.formErrors('#register-form', response.responseJSON, response.status);
            app.recaptchaReset();
        }
    });	
};

// initialize register function
app.register.init = function() {
    app.register.acceptTos();
	$(this.config.form).on('submit', this.process.bind(this));
};

// wait until the document is loaded
$(document).ready(app.register.init());