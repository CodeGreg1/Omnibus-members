"use strict";

// define app.adminTicketsDelete object
app.adminTicketsDelete = {};

// handle app.adminTicketsDelete object configuration
app.adminTicketsDelete.config = {
    button: '#btn-admin-delete-ticket'
};

// handle app.adminTicketsDelete object ajax request
app.adminTicketsDelete.ajax = function(e) {

    $(document).delegate(app.adminTicketsDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this ticket!"),
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
                    var actionButtonContent = button.html();

                    $.ajax({
                        type: 'DELETE',
                        url: '/admin/tickets/delete',
                        data: {id:button.attr('data-id')},
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

// initialize functions of app.adminTicketsDelete object
app.adminTicketsDelete.init = function() {
    app.adminTicketsDelete.ajax();
};

// initialize app.adminTicketsDelete object until the document is loaded
$(document).ready(app.adminTicketsDelete.init());