"use strict";

// define app.adminLanguagesUpdate object
app.adminLanguagesUpdate = {};

// handle app.adminLanguagesUpdate object configuration
app.adminLanguagesUpdate.config = {
    form: '#languages-edit-form'
};

// handle getting language update route
app.adminLanguagesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle ajax request for language update
app.adminLanguagesUpdate.ajax = function(e) {
    $(app.adminLanguagesUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminLanguagesUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminLanguagesUpdate.route(),
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
                app.formErrors(app.adminLanguagesUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// handle initializing language update ajax request
app.adminLanguagesUpdate.init = function() {
    app.adminLanguagesUpdate.ajax();
};

// initialize app.adminLanguagesUpdate object until the document is loaded
$(document).ready(app.adminLanguagesUpdate.init());