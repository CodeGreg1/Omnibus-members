"use strict";

// define app.userTicketsDelete object
app.userTicketsDelete = {};

// handle app.userTicketsDelete object configuration
app.userTicketsDelete.config = {
    button: '.btn-user-tickets-delete'
};

// handle app.userTicketsDelete object ajax request
app.userTicketsDelete.ajax = function(e) {

    $(document).delegate(app.userTicketsDelete.config.button, 'click', function() {

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

                    var actionButton = button.parents('.tb-actions-column').find('.dropdown-toggle');
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'DELETE',
                        url: '/user/tickets/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.userTicketsDatatable.tb.refresh();
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

// initialize functions of app.userTicketsDelete object
app.userTicketsDelete.init = function() {
    app.userTicketsDelete.ajax();
};

// initialize app.userTicketsDelete object until the document is loaded
$(document).ready(app.userTicketsDelete.init());