"use strict";

// define app.ticketReply object
app.ticketReply = {};

// handle app.ticketReply object configuration
app.ticketReply.config = {
    form: '#ticket-reply-form',
    route: '/tickets/reply'
};

// handle app.ticketReply object ajax request
app.ticketReply.ajax = function() {

    $(app.ticketReply.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $routeType = $(app.ticketReply.config.form).attr('data-type');
        var $button = $(app.ticketReply.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($(app.ticketReply.config.form + ' .ticket-reply-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        var attachments = $('#ticket-reply-attachments-dropzone').get(0).dropzone.getAcceptedFiles();
        $.each(attachments, function (key, file) {
            data.append('attachments[' + key + ']', $('#ticket-reply-attachments-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });

        $.ajax({
            type: 'POST',
            url: '/' + $routeType + app.ticketReply.config.route,
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
                app.formErrors(app.ticketReply.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.ticketReply object
app.ticketReply.init = function() {
    app.ticketReply.ajax();

    $('#btn-add-a-reply').on('click', function() {
        $('.convo-reply-form').show();

        $('#btn-add-a-note').attr('disabled', false);
        $('.convo-actions-wrapper').removeClass('bg-warning');
        $('#ticket-reply-attachments-dropzone').parents('.form-group').show();
        $(app.ticketReply.config.form + ' button[type="submit"]').removeClass('btn-warning');
        $(app.ticketReply.config.form + ' [name="is_note"]').remove();

        $(this).attr('disabled', true);
        $('.convo-actions-wrapper').addClass('bg-primary');
        $(app.ticketReply.config.form + ' button[type="submit"]').addClass('btn-primary');
    });

    $('#btn-add-a-note').on('click', function() {
        $('.convo-reply-form').show();

        $('#btn-add-a-reply').attr('disabled', false);
        $('.convo-actions-wrapper').removeClass('bg-primary');
        $('#ticket-reply-attachments-dropzone').parents('.form-group').hide();
        $(app.ticketReply.config.form + ' button[type="submit"]').removeClass('btn-primary');

        $(this).attr('disabled', true);
        $('.convo-actions-wrapper').addClass('bg-warning');
        $(app.ticketReply.config.form + ' button[type="submit"]').addClass('btn-warning');
        $(app.ticketReply.config.form).prepend('<input type="hidden" name="is_note" value="1">');
    });

    $('#btn-close-reply-form').on('click', function() {
        $('.convo-reply-form').hide();

        $('#btn-add-a-reply').attr('disabled', false);
        $('.convo-actions-wrapper').removeClass('bg-primary');

        $('#btn-add-a-note').attr('disabled', false);
        $('.convo-actions-wrapper').removeClass('bg-warning');
    });

    $('#ticket-change-status').on('change', function() {
        var status = $(this).val();
        var ticketId = $(app.ticketReply.config.form + ' [name="ticket_id"]').val();

        $.ajax({
            type: 'POST',
            url: '/admin/tickets/status/' + ticketId + '/change',
            data: JSON.stringify({status:status}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;

                app.notify(response.responseJSON.message);
            }
        }); 
    });
};

// initialize app.ticketReply object until the document is loaded
$(document).ready(app.ticketReply.init());