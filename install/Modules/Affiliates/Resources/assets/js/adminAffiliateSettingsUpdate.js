"use strict";

app.adminAffiliateSettingsUpdate = {};


// handle app.adminWalletSettings object configuration
app.adminAffiliateSettingsUpdate.config = {
    form: '#admin-affiliate-settings-edit-form',
    route: '/admin/settings/affiliates/update',
    redirect: '/admin/settings/affiliates'
};

// handle app.adminWalletSettings object ajax request
app.adminAffiliateSettingsUpdate.ajax = function() {

    $(app.adminAffiliateSettingsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminAffiliateSettingsUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminAffiliateSettingsUpdate.config.route,
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
                    app.redirect(app.adminAffiliateSettingsUpdate.config.redirect);
                }, 1000);

                app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminAffiliateSettingsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });


    });

};

app.adminAffiliateSettingsUpdate.init = function() {
    this.ajax();
};

$(document).ready(app.adminAffiliateSettingsUpdate.init());
