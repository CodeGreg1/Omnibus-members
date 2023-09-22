"use strict";

// define app.adminTicketsCreate object
app.adminTicketsCreate = {};

// handle app.adminTicketsCreate object configuration
app.adminTicketsCreate.config = {
    form: '#admin-tickets-create-form',
    route: '/admin/tickets/create'
};

// handle app.adminTicketsCreate object ajax request
app.adminTicketsCreate.ajax = function() {

    $(app.adminTicketsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminTicketsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($('.admin-tickets-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        var attachments = $('#admin-tickets-attachments-dropzone').get(0).dropzone.getAcceptedFiles();
        $.each(attachments, function (key, file) {
            data.append('attachments[' + key + ']', $('#admin-tickets-attachments-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });

        $.ajax({
            type: 'POST',
            url: app.adminTicketsCreate.config.route,
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
                app.formErrors(app.adminTicketsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminTicketsCreate object
app.adminTicketsCreate.init = function() {
    app.adminTicketsCreate.ajax();
};

// initialize app.adminTicketsCreate object until the document is loaded
$(document).ready(app.adminTicketsCreate.init());