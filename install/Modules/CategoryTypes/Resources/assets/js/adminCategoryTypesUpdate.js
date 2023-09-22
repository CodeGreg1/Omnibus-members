"use strict";

// define app.adminCategoryTypesUpdate object
app.adminCategoryTypesUpdate = {};

// handle app.adminCategoryTypesUpdate object configuration
app.adminCategoryTypesUpdate.config = {
    form: '#admin-category-types-edit-form'
};

// handle app.adminCategoryTypesUpdate object on getting route
app.adminCategoryTypesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.adminCategoryTypesUpdate object ajax request
app.adminCategoryTypesUpdate.ajax = function(e) {
    $(app.adminCategoryTypesUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminCategoryTypesUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminCategoryTypesUpdate.route(),
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
                app.formErrors(app.adminCategoryTypesUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// initialize functions of app.adminCategoryTypesUpdate object
app.adminCategoryTypesUpdate.init = function() {
    app.adminCategoryTypesUpdate.ajax();
};

// initialize app.adminCategoryTypesUpdate object until the document is loaded
$(document).ready(app.adminCategoryTypesUpdate.init());