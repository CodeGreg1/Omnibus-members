"use strict";

// define app.userTicketsCreate object
app.userTicketsCreate = {};

// handle app.userTicketsCreate object configuration
app.userTicketsCreate.config = {
    form: '#user-tickets-create-form',
    route: '/user/tickets/create'
};

// handle app.userTicketsCreate object ajax request
app.userTicketsCreate.ajax = function() {

    $(app.userTicketsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.userTicketsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($('.user-tickets-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        var attachments = $('#user-tickets-attachments-dropzone').get(0).dropzone.getAcceptedFiles();
        $.each(attachments, function (key, file) {
            data.append('attachments[' + key + ']', $('#user-tickets-attachments-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });

        $.ajax({
            type: 'POST',
            url: app.userTicketsCreate.config.route,
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
                app.formErrors(app.userTicketsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.userTicketsCreate object
app.userTicketsCreate.init = function() {
    app.userTicketsCreate.ajax();
};

// initialize app.userTicketsCreate object until the document is loaded
$(document).ready(app.userTicketsCreate.init());