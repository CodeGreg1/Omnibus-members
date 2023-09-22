"use strict";

// define app.adminServicesUpdate object
app.adminServicesUpdate = {};

// handle app.adminServicesUpdate object configuration
app.adminServicesUpdate.config = {
    form: '#admin-services-edit-form'
};

// handle app.adminServicesUpdate object on getting route
app.adminServicesUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.adminServicesUpdate object ajax request
app.adminServicesUpdate.ajax = function(e) {
    $(app.adminServicesUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminServicesUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.each($('.admin-services-tinymce-default'), function() {
            data.append($(this).attr('name'), tinyMCE.get($(this).attr('id')).getContent());
        });

        $.ajax({
            type: 'POST',
            url: app.adminServicesUpdate.route(),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminServicesUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

// initialize functions of app.adminServicesUpdate object
app.adminServicesUpdate.init = function() {
    app.adminServicesUpdate.ajax();
};

// initialize app.adminServicesUpdate object until the document is loaded
$(document).ready(app.adminServicesUpdate.init());