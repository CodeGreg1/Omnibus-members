"use strict";

// define app.adminManualGatewayUpdate object
app.adminManualGatewayUpdate = {};

// handle app.adminManualGatewayUpdate object configuration
app.adminManualGatewayUpdate.config = {
    form: '#admin-manual-gateway-edit-form'
};

// handle app.adminManualGatewayUpdate object on getting route
app.adminManualGatewayUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle app.adminManualGatewayUpdate object ajax request
app.adminManualGatewayUpdate.ajax = function(e) {
    $(app.adminManualGatewayUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminManualGatewayUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        var images = $(`[data-image-gallery="manual-gateway-logo-edit"]`)
                            .sGallery()
                            .images();
        if (images.length) {
            data.append('media_id', images[0].id);
        } else {
            data.append('media_id', '');
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
            url: app.adminManualGatewayUpdate.route(),
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
                app.formErrors(app.adminManualGatewayUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });

    });

};

// initialize functions of app.adminManualGatewayUpdate object
app.adminManualGatewayUpdate.init = function() {
    app.adminManualGatewayUpdate.ajax();
};

// initialize app.adminManualGatewayUpdate object until the document is loaded
$(document).ready(app.adminManualGatewayUpdate.init());
