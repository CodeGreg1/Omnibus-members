"use strict";

app.adminPhotoFolderCreate = {};

// handle app.adminPhotoFolderCreate object configuration
app.adminPhotoFolderCreate.config = {
    form: '#photo-new-folder-form',
    route: '/admin/photos/create'
};

// handle app.adminPhotoFolderCreate object ajax request
app.adminPhotoFolderCreate.ajax = function() {
    const $self = this;
    $($self.config.form).on('submit', function(e) {

        e.preventDefault();
        var $form = $($self.config.form);
        var $button = $form.find(':submit');
        var $content = $button.html();

        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url: $self.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                if ($('[name="gallery-folder-select"]').length) {
                    app.backButtonContent($button, $content);
                    app.notify(response.message);
                    $form.parents('.btn-group').find('.dropdown-toggle').click();
                    $('[name="gallery-folder-select"]').append($('<option>', {
                        value: response.data.folder.id,
                        text: response.data.folder.name
                    }));

                    $('[name="gallery-folder-select"]')
                        .val(response.data.folder.id)
                        .trigger('change');
                } else {
                    app.redirect(response.data.redirectTo);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors($self.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

// initialize functions of app.adminPhotoFolderCreate object
app.adminPhotoFolderCreate.init = function() {
    app.adminPhotoFolderCreate.ajax();
};

// initialize app.adminPhotoFolderCreate object until the document is loaded
$(document).ready(app.adminPhotoFolderCreate.init());
