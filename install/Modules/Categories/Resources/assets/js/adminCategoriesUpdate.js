"use strict";

// define app.adminCategoriesUpdate object
app.adminCategoriesUpdate = {};

// handle app.adminCategoriesUpdate object configuration
app.adminCategoriesUpdate.config = {
    form: '#admin-categories-edit-form'
};

// handle app.adminCategoriesUpdate object on getting route
app.adminCategoriesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.adminCategoriesUpdate object ajax request
app.adminCategoriesUpdate.ajax = function(e) {
    $(app.adminCategoriesUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminCategoriesUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminCategoriesUpdate.route(),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminCategoriesUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// initialize functions of app.adminCategoriesUpdate object
app.adminCategoriesUpdate.init = function() {
    app.adminCategoriesUpdate.ajax();
};

// initialize app.adminCategoriesUpdate object until the document is loaded
$(document).ready(app.adminCategoriesUpdate.init());