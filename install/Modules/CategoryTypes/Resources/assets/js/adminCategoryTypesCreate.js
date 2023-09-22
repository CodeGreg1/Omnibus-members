"use strict";

// define app.adminCategoryTypesCreate object
app.adminCategoryTypesCreate = {};

// handle app.adminCategoryTypesCreate object configuration
app.adminCategoryTypesCreate.config = {
    form: '#admin-category-types-create-form',
    route: '/admin/category-types/create'
};

// handle app.adminCategoryTypesCreate object ajax request
app.adminCategoryTypesCreate.ajax = function() {

    $(app.adminCategoryTypesCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminCategoryTypesCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminCategoryTypesCreate.config.route,
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
                app.formErrors(app.adminCategoryTypesCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminCategoryTypesCreate object
app.adminCategoryTypesCreate.init = function() {
    app.adminCategoryTypesCreate.ajax();
};

// initialize app.adminCategoryTypesCreate object until the document is loaded
$(document).ready(app.adminCategoryTypesCreate.init());