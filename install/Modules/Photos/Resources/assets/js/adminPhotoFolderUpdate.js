"use strict";

app.adminPhotoFolderUpdate = {};

app.adminPhotoFolderUpdate.config = {
    button: '.btn-photos-edit',
    form: '#photo-update-folder-form',
    modal: '#photo-edit-folder-modal',
};

app.adminPhotoFolderUpdate.getRoute = function(id) {
    return `/admin/photos/${id}/update`;
};

app.adminPhotoFolderUpdate.ajax = function() {
    const $self = this;
    $($self.config.form).on('submit', function(e) {
        e.preventDefault();

        var $button = $(this).find(':submit');
        var $content = $button.html();
        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $self.getRoute($(this).find('[name="id"]').val()),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                $($self.config.modal).modal('hide');

                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                setTimeout(() => {
                    app.backButtonContent($button, $content);
                    app.formErrors($self.config.form, response.responseJSON, response.status);
                }, 350);
            }
        });
    });
};

app.adminPhotoFolderUpdate.showEditModal = function() {
    const $self = this;
    $($self.config.button).on('click', function() {
        $($self.config.form).find('[name="id"]').val($(this).data('id'));
        $($self.config.form).find('[name="name"]').val($(this).data('name'));
        $($self.config.form).find('[name="description"]').val($(this).data('description'));
        $($self.config.modal).modal('show');
    });
};

app.adminPhotoFolderUpdate.onModalHide = function() {
    const $self = this;
    $($self.config.modal).on("hidden.bs.modal", function () {
        $($self.config.form).find('[name="id"]').val('');
        $($self.config.form).find('[name="name"]').val('');
        $($self.config.form).find('[name="description"]').val('');
    });
};

app.adminPhotoFolderUpdate.init = function() {
    this.ajax();
    this.showEditModal();
    this.onModalHide();
};

$(document).ready(app.adminPhotoFolderUpdate.init());
