"use strict";

// define app.adminServicesDelete object
app.adminServicesDelete = {};

// handle app.adminServicesDelete object configuration
app.adminServicesDelete.config = {
    button: '.btn-admin-services-delete'
};

// handle app.adminServicesDelete object ajax request
app.adminServicesDelete.ajax = function(e) {

    $(document).delegate(app.adminServicesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this service!"),
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
                        url: '/admin/services/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminServicesDatatable.tb.refresh();
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

// initialize functions of app.adminServicesDelete object
app.adminServicesDelete.init = function() {
    app.adminServicesDelete.ajax();
};

// initialize app.adminServicesDelete object until the document is loaded
$(document).ready(app.adminServicesDelete.init());