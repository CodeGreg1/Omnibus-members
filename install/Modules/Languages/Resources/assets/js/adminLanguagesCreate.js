"use strict";

// define app.adminLanguagesCreate object
app.adminLanguagesCreate = {};

// handle app.adminLanguagesCreate object configuration
app.adminLanguagesCreate.config = {
    form: '#languages-create-form',
    route: '/admin/languages/create'
};

// handle ajax request on creating new language
app.adminLanguagesCreate.ajax = function() {

    $(app.adminLanguagesCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminLanguagesCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminLanguagesCreate.config.route,
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
                app.formErrors(app.adminLanguagesCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminLanguagesCreate object
app.adminLanguagesCreate.init = function() {
    app.adminLanguagesCreate.ajax();
};

// initialize app.adminLanguagesCreate object until the document is loaded
$(document).ready(app.adminLanguagesCreate.init());