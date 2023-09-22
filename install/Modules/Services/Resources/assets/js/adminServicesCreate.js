"use strict";

// define app.adminServicesCreate object
app.adminServicesCreate = {};

// handle app.adminServicesCreate object configuration
app.adminServicesCreate.config = {
    form: '#admin-services-create-form',
    route: '/admin/services/create'
};

// handle app.adminServicesCreate object ajax request
app.adminServicesCreate.ajax = function() {

    $(app.adminServicesCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminServicesCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($('.admin-services-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        $.ajax({
            type: 'POST',
            url: app.adminServicesCreate.config.route,
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
                app.formErrors(app.adminServicesCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminServicesCreate object
app.adminServicesCreate.init = function() {
    app.adminServicesCreate.ajax();
};

// initialize app.adminServicesCreate object until the document is loaded
$(document).ready(app.adminServicesCreate.init());