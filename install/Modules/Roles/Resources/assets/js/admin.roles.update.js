"use strict";

// define app.adminRolesUpdate object
app.adminRolesUpdate = {};

// handle app.adminRolesUpdate object configuration
app.adminRolesUpdate.config = {
    form: '#roles-edit-form'
};

// handle getting app.adminRolesUpdate object form data
app.adminRolesUpdate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle on getting route
app.adminRolesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.adminRolesUpdate object ajax request
app.adminRolesUpdate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: app.adminRolesUpdate.route(),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(response.data.redirectTo);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            // back button content
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.adminRolesUpdate object
app.adminRolesUpdate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.adminRolesUpdate object until the document is loaded
$(document).ready(app.adminRolesUpdate.init());