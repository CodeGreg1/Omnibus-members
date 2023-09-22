"use strict";

app.adminPhotoFolderDelete = {};

app.adminPhotoFolderDelete.config = {
    btn: '.btn-photos-delete'
};

app.adminPhotoFolderDelete.process = function() {
    const self = this;
    $(document).delegate(self.config.btn, 'click', function(e) {
        const $button = $(this);
        const buttonContent = $button.html();
        const route = $(this).data('route');

        bootbox.confirm({
            title: app.trans("Delete folder?"),
            message: app.trans("You're about to delete folder(s)!"),
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> '+app.trans('Cancel')
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> '+app.trans('Delete now')
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: 'DELETE',
                        url: route,
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            setTimeout(() => {
                                app.notify(response.message);
                                app.redirect(response.data.redirectTo);
                            }, 350);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            // Check check if form validation errors
                            setTimeout(() => {
                                app.notify(response.responseJSON.message);
                                app.backButtonContent($button, buttonContent);
                            }, 350);
                        }
                    });
                }
            }
        });
    });
};

// initialize functions of app.adminPhotoFolderDelete object
app.adminPhotoFolderDelete.init = function() {
    app.adminPhotoFolderDelete.process();
};

// initialize app.adminPhotoFolderDelete object until the document is loaded
$(document).ready(app.adminPhotoFolderDelete.init());
