"use strict";

app.adminApproveWithdrawRequest = {};

app.adminApproveWithdrawRequest.config = {
    form: '#withdrawal-confirm-approve-form',
    button: '#btn-approve-withdraw-request',
    modal: '#withdrawal-confirm-approve-modal'
};

app.adminApproveWithdrawRequest.ajax = function() {
    const self = this;
    $(self.config.form).on('submit', function(e) {
        e.preventDefault();

        var $button = $(self.config.form).find(':submit');
        var $content = $button.html();
        const route = $button.data('route');

        var data = new FormData(this);
        var image = $('#confirm-approve-withdrawal-image-dropzone').get(0).dropzone.getAcceptedFiles();
        $.each(image, function (key, file) {
            data.append('image[' + key + ']', $('#confirm-approve-withdrawal-image-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
        });

        $.ajax({
            type: 'POST',
            url: route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                $(self.config.modal).modal('hide');
                setTimeout(() => {
                    app.notify(response.message);
                    app.redirect(response.data.redirectTo);
                }, 350);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(self.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminApproveWithdrawRequest.onModalHide = function() {
    const self = this;
    $(this.config.modal).on('hide.bs.modal', function (event) {
        $(self.config.form).find('[name="note"]').val('');
        $(self.config.form).find('[name="image"]').val(null);
        $(self.config.form).find('ul.dz-preview [data-dz-remove]').map(function() {
            this.click();
        });
    })
};

app.adminApproveWithdrawRequest.init = function() {
    this.ajax();
    this.onModalHide();
};

$(document).ready(app.adminApproveWithdrawRequest.init());
