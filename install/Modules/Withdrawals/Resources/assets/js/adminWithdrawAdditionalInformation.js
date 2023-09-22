"use strict";

app.adminWithdrawAdditionalInformation = {};

app.adminWithdrawAdditionalInformation.config = {
    form: '#admin-withdrawal-additional-info-form'
};

// handle app.adminWithdrawAdditionalInformation object on getting route
app.adminWithdrawAdditionalInformation.route = function(id) {
    return $(this.config.form).data('action');
};

// get email template data
app.adminWithdrawAdditionalInformation.data = function() {
    return $(this.config.form).serializeArray();
};

// handle app.adminWithdrawAdditionalInformation object ajax request
app.adminWithdrawAdditionalInformation.ajax = function() {
    const $self = this;
    $(app.adminWithdrawAdditionalInformation.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminWithdrawAdditionalInformation.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        var image = $('#admin-withdrawal-info-image-dropzone').get(0).dropzone.getAcceptedFiles();
        $.each(image, function (key, file) {
            data.append('image[' + key + ']', $('#admin-withdrawal-info-image-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });

        $.ajax({
            type: 'POST',
            url: app.adminWithdrawAdditionalInformation.route(),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                setTimeout(function() {
                    app.redirect(response.data.redirectTo);
                }, 1000);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminWithdrawAdditionalInformation.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

// initialize functions of app.adminWithdrawAdditionalInformation object
app.adminWithdrawAdditionalInformation.init = function() {
    app.adminWithdrawAdditionalInformation.ajax();
};

// initialize app.adminWithdrawAdditionalInformation object until the document is loaded
$(document).ready(app.adminWithdrawAdditionalInformation.init());
