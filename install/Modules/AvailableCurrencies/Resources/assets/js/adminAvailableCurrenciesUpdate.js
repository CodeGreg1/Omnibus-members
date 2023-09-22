"use strict";

// define adminAvailableCurrenciesUpdate object
app.adminAvailableCurrenciesUpdate = {};

// handle adminAvailableCurrenciesUpdate object configuration
app.adminAvailableCurrenciesUpdate.config = {
    form: '#admin-available-currencies-edit-form'
};

// handle getting update route
app.adminAvailableCurrenciesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle ajax request
app.adminAvailableCurrenciesUpdate.ajax = function(e) {
    $(app.adminAvailableCurrenciesUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminAvailableCurrenciesUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminAvailableCurrenciesUpdate.route(),
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
                app.formErrors(app.adminAvailableCurrenciesUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// call all available functions of app.adminAvailableCurrenciesUpdate object
app.adminAvailableCurrenciesUpdate.init = function() {
    app.adminAvailableCurrenciesUpdate.ajax();
};

// initialize app.adminAvailableCurrenciesUpdate object until the document is loaded
$(document).ready(app.adminAvailableCurrenciesUpdate.init());