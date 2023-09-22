"use strict";

// define app.adminSettingsCreate object
app.adminSettingsCreate = {};

// handle app.adminSettingsCreate object configuration
app.adminSettingsCreate.config = {
    form: '#admin-settings-edit-form',
    route: '/admin/settings/update',
    redirect: '/admin/settings'
};

// handle app.adminSettingsCreate object ajax request
app.adminSettingsCreate.ajax = function() {

    $(app.adminSettingsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminSettingsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        if($('#settings-default_profile_photo-dropzone').length) {
            var default_profile_photo = $('#settings-default_profile_photo-dropzone').get(0).dropzone.getAcceptedFiles();
            $.each(default_profile_photo, function (key, file) {
                data.append('default_profile_photo[' + key + ']', $('#settings-default_profile_photo-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
        }

        if($('#settings-colored_logo-dropzone').length) {
            var colored_logo = $('#settings-colored_logo-dropzone').get(0).dropzone.getAcceptedFiles();
            $.each(colored_logo, function (key, file) {
                data.append('colored_logo[' + key + ']', $('#settings-colored_logo-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
        }

        if($('#settings-white_logo-dropzone').length) {
            var white_logo = $('#settings-white_logo-dropzone').get(0).dropzone.getAcceptedFiles();
            $.each(white_logo, function (key, file) {
                data.append('white_logo[' + key + ']', $('#settings-white_logo-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
        }

        if($('#settings-favicon-dropzone').length) {
            var favicon = $('#settings-favicon-dropzone').get(0).dropzone.getAcceptedFiles();
            $.each(favicon, function (key, file) {
                data.append('favicon[' + key + ']', $('#settings-favicon-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
        }

        if($('#settings-paypal_logo-dropzone').length) {
            var paypal_logo = $('#settings-paypal_logo-dropzone').get(0).dropzone.getAcceptedFiles();
            $.each(paypal_logo, function (key, file) {
                data.append('paypal_logo[' + key + ']', $('#settings-paypal_logo-dropzone')[0].dropzone.getAcceptedFiles()[key]); // attach dropzone image element
            });
        }


        $.ajax({
            type: 'POST',
            url: app.adminSettingsCreate.config.route,
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
                    var redirect = $(app.adminSettingsCreate.config.form + ' [name="redirect"]');
                    
                    if(!redirect.length) {
                        redirect = app.adminSettingsCreate.config.redirect;
                    } else {
                        redirect = redirect.val();
                    }

                    app.redirect(redirect);
                }, 1000);

                app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminSettingsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminSettingsCreate object
app.adminSettingsCreate.init = function() {
    app.adminSettingsCreate.ajax();
};

// initialize app.adminSettingsCreate object until the document is loaded
$(document).ready(app.adminSettingsCreate.init());