"use strict";

// define app.ticketMediaDelete object
app.ticketMediaDelete = {};

// handle app.ticketMediaDelete object configuration
app.ticketMediaDelete.config = {
    button: '.ticket-remove-attachement'
};

// handle removing media
app.ticketMediaDelete.removeElement = function(button) {
    var attachment = button.parents('li');
    var attachmentsWrapper = attachment.parents('.conversation-attachments-wrapper');
    var totalAttachments = attachmentsWrapper.find('.conversation-attachments').find('li').length;

    if(totalAttachments > 1) {
        button.parent('li').remove();
    } else {
        attachmentsWrapper.remove();
    }
};

// handle app.ticketMediaDelete object ajax request
app.ticketMediaDelete.ajax = function(e) {

    $(document).delegate(app.ticketMediaDelete.config.button, 'click', function() {

        var button = $(this);

        if(button.attr('disabled') !== undefined) {
            return;
        }

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this attachment!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {

                    var actionButton = button;
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'DELETE',
                        url: button.attr('data-route'),
                        data: {uid:button.attr('data-uid')},
                        beforeSend: function () {
                            actionButton.attr('disabled', 'disabled');
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            actionButton.removeAttr('disabled');
                            app.notify(response.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                            app.ticketMediaDelete.removeElement(actionButton);
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                        }
                    });
                }
            }
        });
    });
     
};

// initialize functions of app.ticketMediaDelete object
app.ticketMediaDelete.init = function() {
    app.ticketMediaDelete.ajax();
};

// initialize app.ticketMediaDelete object until the document is loaded
$(document).ready(app.ticketMediaDelete.init());