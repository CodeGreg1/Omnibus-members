"use strict";

// define app.updateProfileAvatar object
app.updateProfileAvatar = {};

// handle app.updateProfileAvatar object configuration
app.updateProfileAvatar.config = {
    image: null,
    uploadPhoto: null,
    avatarId: '#avatar',
    fileUploadPhotoId: '#file-upload-photo',
    btnTriggerUploadPhotoId: '#btn-trigger-upload-photo',
    avatarControlsClass: '.avatar-controls',
    avatarWrapperClass: '.avatar-wrapper',
    btnUploadPhotoId: '#btn-upload-photo',
    btnCancelPhotoId: '#btn-cancel-upload',
    profileAvatarClass: '.profile-avatar',
	route: '/profile/avatar'
};

// handle on showing modal for updating profile picture
app.updateProfileAvatar.showModal = function() {
    $('[data-target="#general-profile-upload-photo"]').on('click', function() {
        app.updateProfileAvatar.config.uploadPhoto = null;
        $(app.updateProfileAvatar.config.avatarId).croppie('destroy');
        $(app.updateProfileAvatar.config.avatarId).removeClass('croppie-container');
        app.updateProfileAvatar.showButtonTriggerUploadPhoto();
        app.updateProfileAvatar.hideAvatarWapper();
        app.updateProfileAvatar.hideAvatarControls();
    });
};

// handle on trigger upload file for profile picture
app.updateProfileAvatar.triggerUploadFile = function() {
    $(app.updateProfileAvatar.config.btnTriggerUploadPhotoId).on('click', function() {
        $(app.updateProfileAvatar.config.fileUploadPhotoId).trigger('click');
    });
};

// handle profile picture avatar on cropping photo
app.updateProfileAvatar.initCroppie = function() {
    var avatar = $(app.updateProfileAvatar.config.avatarId);
    var width = $(app.updateProfileAvatar.config.avatarWrapperClass).width(),
        bWidth = 300,
        vWidth = 150;


    avatar.croppie('destroy');
    app.updateProfileAvatar.config.uploadPhoto = avatar.croppie({
        enableExif: true,
        viewport: {
            width: vWidth,
            height: vWidth,
            type: 'circle'
        },
        boundary: {
            width: bWidth,
            height: bWidth
        }
    });

    if (app.updateProfileAvatar.config.image) {
        app.updateProfileAvatar.config.uploadPhoto.croppie('bind', {
            url: app.updateProfileAvatar.config.image
        });
    }
};

// handle on changing file upload photo
app.updateProfileAvatar.fileUploadPhotoChange = function() {
    $(app.updateProfileAvatar.config.fileUploadPhotoId).on('change', function () { 
        var reader = new FileReader();

        reader.onload = function (e) {
            app.updateProfileAvatar.config.image = e.target.result;
            app.updateProfileAvatar.initCroppie();

            app.updateProfileAvatar.hideButtonTriggerUploadPhoto();
            app.updateProfileAvatar.showAvatarWapper();
            app.updateProfileAvatar.showAvatarControls();
        }

        reader.readAsDataURL(this.files[0]);
    });
};

// handle uploading photo
app.updateProfileAvatar.uploadPhoto = function() {
    $(app.updateProfileAvatar.config.btnUploadPhotoId).on('click', function (ev) {
        app.updateProfileAvatar.config.uploadPhoto.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {

            var $button = $(app.updateProfileAvatar.config.btnUploadPhotoId);
            var $content = $button.html();

            $.ajax({
                url: app.updateProfileAvatar.config.route,
                type: 'POST',
                data: {'avatar':resp},
                beforeSend: function () {
                    app.buttonLoader($button);
                },
                success: function (response) {
                    app.notify(response.message);
                    app.updateProfileAvatar.appendAvatar(response.data.path);
                    app.closeModal();
                },
                complete: function (xhr) {
                    app.backButtonContent($button, $content);
                },
                error: function (response) {
                    app.notify(response.responseJSON.message);
                    app.closeModal();
                }
            });
        });
    });
};

// handle appending updated avatar to the image element
app.updateProfileAvatar.appendAvatar = function(path) {
    $(app.updateProfileAvatar.config.profileAvatarClass).attr('src', path);
};

// handle cancelling upload
app.updateProfileAvatar.cancelUpload = function() {
    $(app.updateProfileAvatar.config.btnCancelPhotoId).click(function () {
        app.updateProfileAvatar.config.uploadPhoto = null;
        $(app.updateProfileAvatar.config.avatarId).croppie('destroy');
        $(app.updateProfileAvatar.config.avatarId).removeClass('croppie-container');
        app.updateProfileAvatar.showButtonTriggerUploadPhoto();
        app.updateProfileAvatar.hideAvatarWapper();
        app.updateProfileAvatar.hideAvatarControls();
    });
};

// handle on trigger window resize for uploading profile photo
app.updateProfileAvatar.triggerWindowResize = function() {
    var timer;

    $(window).resize(function () {
        if (app.updateProfileAvatar.config.uploadPhoto) {
            timer && clearTimeout(timer);
            timer = setTimeout(app.updateProfileAvatar.initCroppie(), 100);
        }
    });
};

// handle hiding on avatar
app.updateProfileAvatar.hideAvatarWapper = function() {
    $(app.updateProfileAvatar.config.avatarWrapperClass).hide();
};

// handle showing on avatar 
app.updateProfileAvatar.showAvatarWapper = function() {
    $(app.updateProfileAvatar.config.avatarWrapperClass).show();
};

// handle on hiding avatar with controls
app.updateProfileAvatar.hideAvatarControls = function() {
    $(app.updateProfileAvatar.config.avatarControlsClass).hide();
};

// handle on showing avatar with controls
app.updateProfileAvatar.showAvatarControls = function() {
    $(app.updateProfileAvatar.config.avatarControlsClass).show();
};

// handle on hidding button with the trigger on photo upload
app.updateProfileAvatar.hideButtonTriggerUploadPhoto = function() {
    $(app.updateProfileAvatar.config.btnTriggerUploadPhotoId).hide();
};

// handle on showing button with the trigger on photo upload
app.updateProfileAvatar.showButtonTriggerUploadPhoto = function() {
    $(app.updateProfileAvatar.config.btnTriggerUploadPhotoId).show();
};

// initialize functions of app.updateProfileAvatar object
app.updateProfileAvatar.init = function() {
    this.showModal();
	this.triggerUploadFile();
    this.triggerWindowResize();
    this.fileUploadPhotoChange();
    this.uploadPhoto();
    this.cancelUpload();
};

// initialize app.updateProfileAvatar object until the document is loaded
$(document).ready(app.updateProfileAvatar.init());