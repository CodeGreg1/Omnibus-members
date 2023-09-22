"use strict";

// define app.adminManualGatewayCreate object
app.adminManualGatewayCreate = {};

// handle app.adminManualGatewayCreate object configuration
app.adminManualGatewayCreate.config = {
    form: '#admin-manual-gateway-create-form',
    route: '/admin/manual-gateways/create'
};

// get email template data
app.adminManualGatewayCreate.data = function() {
    var data = $(this.config.form).serializeArray();
    data.push({name: 'content', value: tinyMCE.get('create-email-template-content').getContent()});

    var images = $(`[data-image-gallery="manual-gateway-logo-create"]`)
                            .sGallery()
                            .images();
    if (images.length) {
        data.push({
            name: 'media_id',
            value: images[0].id
        });
    }

    return data;
};

// handle app.adminManualGatewayCreate object ajax request
app.adminManualGatewayCreate.ajax = function() {

    $(app.adminManualGatewayCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminManualGatewayCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        var images = $(`[data-image-gallery="manual-gateway-logo-create"]`)
                            .sGallery()
                            .images();
        if (images.length) {
            data.append('media_id', images[0].id);
        }

        data.append('instructions', tinyMCE.get('admin-manual-gateway-instructions').getContent());

        if ($(app.adminManualGateway.config.userDataList).find('.user-data-row').length) {
            data.append(
                'user_data',
                JSON.stringify($(app.adminManualGateway.config.userDataList)
                    .find('.user-data-row')
                    .map(function(i, item) {
                        return {
                            field_name: $(item).find('[name="field_name"]').val(),
                            field_type: $(item).find('[name="field_type"]').val(),
                            required: $(item).find('[name="required"]').val() === 'true' ? 1 : 0
                        };
                    }).get())
            );
        }

        $.ajax({
            type: 'POST',
            url: app.adminManualGatewayCreate.config.route,
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
                app.formErrors(app.adminManualGatewayCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

// initialize functions of app.adminManualGatewayCreate object
app.adminManualGatewayCreate.init = function() {
    app.adminManualGatewayCreate.ajax();
};

// initialize app.adminManualGatewayCreate object until the document is loaded
$(document).ready(app.adminManualGatewayCreate.init());
