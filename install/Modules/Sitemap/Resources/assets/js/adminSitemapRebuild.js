"use strict";

app.adminSitemapRebuild = {};

app.adminSitemapRebuild.config = {
    btn: '#admin-sitemap-re-build',
    route: '/admin/sitemap/re-build'
};

app.adminSitemapRebuild.ajax = function(e) {
    const $button = $(e.target);
    const $content = $button.html();

    $.ajax({
            type: 'POST',
            url: app.adminSitemapRebuild.config.route,
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
                app.notify(response.responseJSON.message);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
};

app.adminSitemapRebuild.init = function() {
    $(app.adminSitemapRebuild.config.btn).on('click', app.adminSitemapRebuild.ajax.bind(this));
};

$(document).ready(app.adminSitemapRebuild.init());
