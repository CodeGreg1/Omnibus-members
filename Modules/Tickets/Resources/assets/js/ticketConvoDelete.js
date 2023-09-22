"use strict";

// define app.ticketConvoDelete object
app.ticketConvoDelete = {};

// handle app.ticketConvoDelete object configuration
app.ticketConvoDelete.config = {
    button: '.btn-delete-convo'
};

// handle app.ticketConvoDelete object ajax request
app.ticketConvoDelete.ajax = function(e) {

    $(document).delegate(app.ticketConvoDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this convo!"),
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

                    var actionButton = button.parents('.tb-actions-column').find('.dropdown-toggle');
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'DELETE',
                        url: button.attr('data-route'),
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.redirect(response.data.redirectTo);
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

// initialize functions of app.ticketConvoDelete object
app.ticketConvoDelete.init = function() {
    app.ticketConvoDelete.ajax();
};

// initialize app.ticketConvoDelete object until the document is loaded
$(document).ready(app.ticketConvoDelete.init());