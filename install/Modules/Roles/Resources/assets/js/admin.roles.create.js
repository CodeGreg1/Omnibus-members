"use strict";

// define app.adminRoleCreate object
app.adminRoleCreate = {};

// handle app.adminRoleCreate object configuration
app.adminRoleCreate.config = {
    form: '#roles-create-form',
    route: '/admin/roles/create'
};

// handle getting app.adminRoleCreate object form data
app.adminRoleCreate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.adminRoleCreate object ajax request
app.adminRoleCreate.process = function(e) {
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
            // back button content
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.adminRoleCreate object
app.adminRoleCreate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.adminRoleCreate object until the document is loaded
$(document).ready(app.adminRoleCreate.init());