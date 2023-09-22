"use strict";

// define app.userTicketsUpdate object
app.userTicketsUpdate = {};

// handle app.userTicketsUpdate object configuration
app.userTicketsUpdate.config = {
    form: '#user-tickets-edit-form'
};

// handle app.userTicketsUpdate object on getting route
app.userTicketsUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.userTicketsUpdate object ajax request
app.userTicketsUpdate.ajax = function(e) {
    $(app.userTicketsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.userTicketsUpdate.config.form).find(':submit');
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
            url: app.userTicketsUpdate.route(),
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
                app.formErrors(app.userTicketsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// initialize functions of app.userTicketsUpdate object
app.userTicketsUpdate.init = function() {
    app.userTicketsUpdate.ajax();
};

// initialize app.userTicketsUpdate object until the document is loaded
$(document).ready(app.userTicketsUpdate.init());