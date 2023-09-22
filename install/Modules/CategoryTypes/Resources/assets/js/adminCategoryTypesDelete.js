"use strict";

// define app.adminCategoryTypesDelete object
app.adminCategoryTypesDelete = {};

// handle app.adminCategoryTypesDelete object configuration
app.adminCategoryTypesDelete.config = {
    button: '.btn-admin-categorytypes-delete'
};

// handle app.adminCategoryTypesDelete object ajax request
app.adminCategoryTypesDelete.ajax = function(e) {

    $(document).delegate(app.adminCategoryTypesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this categorytype!"),
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
                        url: '/admin/category-types/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoryTypesDatatable.tb.refresh();
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

// initialize functions of app.adminCategoryTypesDelete object
app.adminCategoryTypesDelete.init = function() {
    app.adminCategoryTypesDelete.ajax();
};

// initialize app.adminCategoryTypesDelete object until the document is loaded
$(document).ready(app.adminCategoryTypesDelete.init());