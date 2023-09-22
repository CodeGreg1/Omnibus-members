"use strict";

// define app.adminPermissionCreate object
app.adminPermissionCreate = {};

// handle app.adminPermissionCreate object configuration
app.adminPermissionCreate.config = {
    form: '#permission-create-form',
    route: '/admin/permissions/create'
};

// handle getting app.adminPermissionCreate object form data
app.adminPermissionCreate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.adminPermissionCreate object ajax request
app.adminPermissionCreate.process = function(e) {
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

// initialize functions of app.adminPermissionCreate object
app.adminPermissionCreate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.adminPermissionCreate object until the document is loaded
$(document).ready(app.adminPermissionCreate.init());