"use strict";

app.adminSitemap = {};

app.adminSitemap.init = function() {
    $('[name="sitemap_auto_rebuild"]').on('change', function() {
        if (this.checked) {
            $('.sitemap-rebuild-frequency').removeClass('d-none');
        } else {
            $('.sitemap-rebuild-frequency').addClass('d-none');
        }
    });
};

$(document).ready(app.adminSitemap.init());
