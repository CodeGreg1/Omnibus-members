"use strict";

// define app.adminCategoriesDelete object
app.adminCategoriesDelete = {};

// handle app.adminCategoriesDelete object configuration
app.adminCategoriesDelete.config = {
    button: '.btn-admin-categories-delete'
};

// handle app.adminCategoriesDelete object ajax request
app.adminCategoriesDelete.ajax = function(e) {

    $(document).delegate(app.adminCategoriesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to delete this category!"),
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
                        url: '/admin/categories/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoriesDatatable.tb.refresh();
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

// initialize functions of app.adminCategoriesDelete object
app.adminCategoriesDelete.init = function() {
    app.adminCategoriesDelete.ajax();
};

// initialize app.adminCategoriesDelete object until the document is loaded
$(document).ready(app.adminCategoriesDelete.init());