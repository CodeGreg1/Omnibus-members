"use strict";

// define app.adminDashboardWidgetsDelete object
app.adminDashboardWidgetsDelete = {};

// handle app.adminDashboardWidgetsDelete object configuration
app.adminDashboardWidgetsDelete.config = {
    button: '.btn-admin-dashboardwidgets-delete'
};

// handle ajax request for deleting dashboard widget
app.adminDashboardWidgetsDelete.ajax = function(e) {

    $(document).delegate(app.adminDashboardWidgetsDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this dashboardwidget!"),
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
                    $.ajax({
                        type: 'DELETE',
                        url: '/admin/dashboard-widgets/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminDashboardWidgetsDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
     
};

// initialize the ajax dashboard widget
app.adminDashboardWidgetsDelete.init = function() {
    app.adminDashboardWidgetsDelete.ajax();
};

// initialize the app.adminDashboardWidgetsDelete object until the document is loaded
$(document).ready(app.adminDashboardWidgetsDelete.init());