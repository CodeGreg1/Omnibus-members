"use strict";

app.adminPageDuplicate = {};

// handle app.adminPageDuplicate object configuration
app.adminPageDuplicate.config = {
    modal: '#duplicate-page-modal',
    form: '#admin-page-duplicate-form',
    btn: {
        save: '#btn-admin-save-duplicate-page',
        edit: '#btn-admin-save-edit-duplicate-page'
    }
};

// handle app.adminPageDuplicate object ajax request
app.adminPageDuplicate.ajax = function() {
    const $self = this;
    $($self.config.form).on('submit', function(e) {

        e.preventDefault();
        var $form = $($self.config.form);
        var $button = $($self.config.btn.save);
        const route = $form.data('route');
        var $content = $button.html();
        const $btnDP = $button.closest('.btn-group').find('.dropdown-toggle');

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
                $btnDP.addClass('disabled').attr('disabled', true);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                $btnDP.removeClass('disabled').attr('disabled', false);
                if (response.data && response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                } else {
                    app.notify(response.message);
                    app.adminPageDatatable.table.refresh();
                }
                $($self.config.modal).modal('hide');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors($self.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
                $btnDP.removeClass('disabled').attr('disabled', false);
            }
        });
    });
};

// initialize functions of app.adminPageDuplicate object
app.adminPageDuplicate.init = function() {
    const $self = this;
    app.adminPageDuplicate.ajax();
    $(this.config.btn.save).on('click', function() {
        $($self.config.modal).find('[name="view_edit"]').val(0);
        $($self.config.modal).find('form').submit();
    });
    $(this.config.btn.edit).on('click', function() {
        $($self.config.modal).find('[name="view_edit"]').val(1);
        $($self.config.modal).find('form').submit();
    });
};

// initialize app.adminPageDuplicate object until the document is loaded
$(document).ready(app.adminPageDuplicate.init());
