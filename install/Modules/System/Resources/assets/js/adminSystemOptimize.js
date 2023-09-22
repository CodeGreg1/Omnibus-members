"use strict";

app.adminSystemOptimize = {};

app.adminSystemOptimize.config = {
    btn: '#btn-optimize-application',
    route: '/admin/system-optimize'
};

app.adminSystemOptimize.ajax = function(e) {
    var $self = this;

     $(app.adminSystemOptimize.config.btn).on('click', function(e) {

        e.preventDefault();

        var $button = $(this);
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: app.adminSystemOptimize.config.route,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                app.notify(response.message);
                setTimeout(function() {
                    window.location = window.location.href;
                }, 2000);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.notify(response.responseJSON.message);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminSystemOptimize.init = function() {
    app.adminSystemOptimize.ajax();
};

$(document).ready(app.adminSystemOptimize.init());
