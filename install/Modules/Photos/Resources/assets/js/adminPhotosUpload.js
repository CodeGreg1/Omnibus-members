"use strict";

app.adminPhotosUpload = {};

app.adminPhotosUpload.config = {
    modal: '#admin-upload-photo-modal',
    form: '#admin-upload-photos-form',
    btnAdd: '.btn-add-photo-upload',
    btnUpload: '.btn-upload-photos',
    btnRemoveImage: '.btn-remove-photo-upload-image',
    photos: [],
    maxSize: 2000000
};

app.adminPhotosUpload.uid = function(){
    return Date.now().toString(36) + Math.random().toString(36).substr(2);
};

app.adminPhotosUpload.addPhoto = function() {
    $(this.config.btnAdd).on('click', function() {
        $(this).closest('form').find('input').trigger('click');
    });
};

app.adminPhotosUpload.upload = function() {
    const $self = this;
    $(this.config.form).on('submit', function(e) {
        e.preventDefault();

        var $form = $($self.config.form);
        var $button = $form.find(':submit');
        var $content = $button.html();

        if ($($self.config.form).find('.photo-upload-images .list-group-item').length > 10) {
            app.formMessageHelper.showFormMessage($self.config.form, app.trans('Upload must not greater than 10 photos.. Please remove some images to continue'), 413);
            $($self.config.modal).animate({scrollTop:0});
            return;
        }

        var data = new FormData();

        data.append('folder', $form.find('[name="folder"]').val());
        var size = 0;

        $($self.config.form).find('.photo-upload-images .list-group-item')
                .map(function(i, element) {
                    const id = $(element).data('id');
                    const file = $self.config.photos.find(item => item.id === id);
                    size = size + file.file.size;
                    data.append(`photos[${i}]`, file.file);
                    if (file.file.size > $self.config.maxSize) {
                        $(element).addClass('list-group-item-error');
                    }
                });

        $.ajax({
            type: 'POST',
            url: '/admin/photos/upload',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                setTimeout(function() {
                    app.notify(response.message);
                    if (app.adminPhotosDatatable.table) {
                        app.backButtonContent($button, $content);
                        app.adminPhotosDatatable.table.refresh();
                    } else {
                        app.redirect(response.data.redirectTo);
                    }
                    $($self.config.modal).modal('hide');
                }, 350);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                if (response.status === 413) {
                    app.formMessageHelper.showFormMessage($self.config.form, response.statusText, response.status);
                    app.notify(response.statusText);
                } else {
                    app.formErrors($self.config.form, response.responseJSON, response.status);
                }
                $($self.config.modal).animate({scrollTop:0});
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminPhotosUpload.photoInputChange = function() {
    const $self = this;
    $('#admin-upload-photos-form input').on('change', function(e) {
        if (this.files.length) {
            for (let index = 0; index < this.files.length; index++) {
                const file = this.files[index];
                const d = {
                    id: $self.uid(),
                    file: file
                };
                $self.config.photos.push(d);

                var reader = new FileReader();
                reader.onload = function(event) {
                    var html = `<li class="list-group-item" data-id="${d.id}">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar">
                                                <img class="rounded" src="${event.target.result}" alt="${d.file.name}">
                                            </div>
                                        </div>
                                        <div class="col overflow-hidden">
                                            <h6 class="text-sm mb-0 image-name">${d.file.name}</h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="javascript:void(0)" class="dropdown-item btn-remove-photo-upload-image">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>`;
                    $($self.config.form).find('.photo-upload-images').append(html);
                }

                reader.readAsDataURL(d.file);
            }
        }

        $(this).val('');
    });
};

app.adminPhotosUpload.removeImage = function() {
    const $self = this;
    $(document).delegate($self.config.form + ' .btn-remove-photo-upload-image', 'click', function() {
        const $li = $(this).closest('li');
        $self.config.photos = $self.config.photos.filter(item => item.id !== $li.data('id'));
        $li.remove();
    });
};

app.adminPhotosUpload.modalOnHide = function() {
    const $self = this;
    $($self.config.modal).on("hidden.bs.modal", function () {
        $self.config.photos = [];
        $($self.config.form).find('.photo-upload-images').html('');
    });
};

// initialize functions of app.adminPhotosUpload object
app.adminPhotosUpload.init = function() {
    app.adminPhotosUpload.addPhoto();
    app.adminPhotosUpload.photoInputChange();
    app.adminPhotosUpload.upload();
    app.adminPhotosUpload.modalOnHide();
    app.adminPhotosUpload.removeImage();
};

// initialize app.adminPhotosUpload object until the document is loaded
$(document).ready(app.adminPhotosUpload.init());
