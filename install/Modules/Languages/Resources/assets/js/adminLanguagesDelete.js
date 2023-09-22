"use strict";

// define app.adminLanguagesDelete object
app.adminLanguagesDelete = {};

// handle app.adminLanguagesDelete object configuration
app.adminLanguagesDelete.config = {
    button: '.btn-languages-delete'
};

// handle ajax request for language delete
app.adminLanguagesDelete.ajax = function(e) {

    $(document).delegate(app.adminLanguagesDelete.config.button, 'click', function() {

        var button = $(this);

        bootbox.confirm({
            title: app.trans("Do you want to continue?"),
            message: app.trans("All users who were using this language will be reverted to the default language if it is deleted."),
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
                        url: '/admin/languages/delete',
                        data: {id:button.attr('data-id')},
                        beforeSend: function () {
                            app.buttonLoader(button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.redirect(response.data.redirectTo);
                        },
                        error: function(response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
     
};

// initialize language delete ajax
app.adminLanguagesDelete.init = function() {
    app.adminLanguagesDelete.ajax();
};

// initialize app.adminLanguagesDelete object until the document is loaded
$(document).ready(app.adminLanguagesDelete.init());