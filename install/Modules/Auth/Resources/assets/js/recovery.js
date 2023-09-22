"use strict";

// define recovery object
app.recovery = {};

// handle recovery object configuration
app.recovery.config = {
	form: '#recovery-form',
	route: '/recovery'
};

// handle recovery object form data
app.recovery.data = function() {
	return $(this.config.form).serializeArray();
};

// handle recovery object ajax request
app.recovery.process = function(e) {
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

// initialize recovery object function
app.recovery.init = function() {
	$(this.config.form).on('submit', this.process.bind(this));
};

// wait until the document is loaded
$(document).ready(app.recovery.init());