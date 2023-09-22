"use strict";

// define app.modulesDelete object
app.modulesDelete = {};

// handle app.modulesDelete object configuration
app.modulesDelete.config = {
    button: '.btn-modules-delete',
};

// handle on ajax request for deleting module
app.modulesDelete.ajax = function(e) {

    $(document).delegate(app.modulesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans("You're about to remove this module!"),
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
                        url: '/admin/module/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function() {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.redirect(response.data.redirectTo);
                        },
                        error: function(response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                        }
                    });
                }
            }
        });
    });
     
};

// initializing module delete function
app.modulesDelete.init = function() {
    app.modulesDelete.ajax();
};

// initialize app.modulesDelete object until the document is loaded
$(document).ready(app.modulesDelete.init());