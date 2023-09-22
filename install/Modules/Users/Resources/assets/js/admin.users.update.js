"use strict";

// define app.usersUpdate object
app.usersUpdate = {};

// handle app.usersUpdate object configuration
app.usersUpdate.config = {
    form: '#user-details-form'
};

// handle getting app.usersUpdate object form data
app.usersUpdate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle on getting route for updating user
app.usersUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.usersUpdate object ajax request
app.usersUpdate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: app.usersUpdate.route(),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            location.reload();
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.usersUpdate object
app.usersUpdate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.usersUpdate object until the document is loaded
$(document).ready(app.usersUpdate.init());