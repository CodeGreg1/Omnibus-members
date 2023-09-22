"use strict";

// define adminAvailableCurrenciesCreate object
app.adminAvailableCurrenciesCreate = {};

// handle adminAvailableCurrenciesCreate object configuration
app.adminAvailableCurrenciesCreate.config = {
    form: '#admin-available-currencies-create-form',
    route: '/admin/currencies/create'
};

// handle adminAvailableCurrenciesCreate object ajax request
app.adminAvailableCurrenciesCreate.ajax = function() {

    $(app.adminAvailableCurrenciesCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminAvailableCurrenciesCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminAvailableCurrenciesCreate.config.route,
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
                app.formErrors(app.adminAvailableCurrenciesCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// call all available functions of adminAvailableCurrenciesCreate object
app.adminAvailableCurrenciesCreate.init = function() {
    app.adminAvailableCurrenciesCreate.ajax();
};

// initialize adminAvailableCurrenciesCreate object until the document is loaded
$(document).ready(app.adminAvailableCurrenciesCreate.init());