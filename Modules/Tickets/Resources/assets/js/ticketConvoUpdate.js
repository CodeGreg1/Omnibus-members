"use strict";

// define app.ticketConvoUpdate object
app.ticketConvoUpdate = {};

// handle app.ticketConvoUpdate object configuration
app.ticketConvoUpdate.config = {
    form: '#edit-ticket-convo-form'
};

// handle app.ticketConvoUpdate object ajax request
app.ticketConvoUpdate.ajax = function() {
    $(app.ticketConvoUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $route = $(app.ticketConvoUpdate.config.form).attr('data-route');
        var $button = $(app.ticketConvoUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($(app.ticketConvoUpdate.config.form + ' .ticket-reply-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        $.ajax({
            type: 'POST',
            url: $route,
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
                app.formErrors(app.ticketConvoUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
};

// initialize functions of app.ticketConvoUpdate object
app.ticketConvoUpdate.init = function() {
    app.ticketConvoUpdate.ajax();

    $(".btn-edit-convo").click(function() {
        var $route = $(this).attr('data-route');
        var $body = $(this).parents('.media-body').find('.media-description').html();
        
        $('#edit-ticket-convo-modal').modal('show');

        $('#edit-ticket-convo-modal form').attr('id', app.ticketConvoUpdate.config.form.substring(1));
        $('#edit-ticket-convo-modal form').attr('data-route', $route);

        tinymce.get('update_convo_message').setContent($body);
    });
};

// initialize app.ticketConvoUpdate object until the document is loaded
$(document).ready(app.ticketConvoUpdate.init());