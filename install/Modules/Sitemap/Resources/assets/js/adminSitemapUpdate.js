"use strict";

app.adminSitemapUpdate = {};

app.adminSitemapUpdate.config = {
    form: '#admin-sitemap-settings-update-form',
    route: '/admin/sitemap/update'
};

app.adminSitemapUpdate.ajax = function(e) {
    var $self = this;

     $(app.adminSitemapUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(app.adminSitemapUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminSitemapUpdate.config.route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                app.notify(response.message);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminSitemapUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminSitemapUpdate.init = function() {
    app.adminSitemapUpdate.ajax();
};

$(document).ready(app.adminSitemapUpdate.init());
