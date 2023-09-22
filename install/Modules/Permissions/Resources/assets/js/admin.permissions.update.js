"use strict";

// define app.adminPermissionUpdate object
app.adminPermissionUpdate = {};

// handle app.adminPermissionUpdate object configuration
app.adminPermissionUpdate.config = {
    form: '#permission-edit-form'
};

// handle getting app.adminPermissionUpdate object form data
app.adminPermissionUpdate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle getting route for updating permission
app.adminPermissionUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle updating permission with ajax request
app.adminPermissionUpdate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: app.adminPermissionUpdate.route(),
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
            // reset button
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.adminPermissionUpdate object
app.adminPermissionUpdate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.adminPermissionUpdate object until the document is loaded
$(document).ready(app.adminPermissionUpdate.init());