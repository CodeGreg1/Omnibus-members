"use strict";

// define app.adminCategoriesCreate object
app.adminCategoriesCreate = {};

// handle app.adminCategoriesCreate object configuration
app.adminCategoriesCreate.config = {
    form: '#admin-categories-create-form',
    route: '/admin/categories/create'
};

// handle app.adminCategoriesCreate object ajax request
app.adminCategoriesCreate.ajax = function() {

    $(app.adminCategoriesCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminCategoriesCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminCategoriesCreate.config.route,
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
                app.formErrors(app.adminCategoriesCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminCategoriesCreate object
app.adminCategoriesCreate.init = function() {
    app.adminCategoriesCreate.ajax();
};

// initialize app.adminCategoriesCreate object until the document is loaded
$(document).ready(app.adminCategoriesCreate.init());