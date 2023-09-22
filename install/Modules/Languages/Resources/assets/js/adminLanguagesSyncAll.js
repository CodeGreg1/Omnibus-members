"use strict";

// define app.adminLanguagesSyncAll object
app.adminLanguagesSyncAll = {};

// handle app.adminLanguagesSyncAll object configuration
app.adminLanguagesSyncAll.config = {
	route: '/admin/languages/sync-all-language'
};

// handle app.adminLanguagesSyncAll initialization for ajax request
app.adminLanguagesSyncAll.init = function() {
	$('#btn-sync-all-language').on('click', function() {
        var $button = $(this);
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: app.adminLanguagesSyncAll.config.route,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
            	app.notify(response.message);
            	app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                app.notify(response.responseJSON.message);
                app.backButtonContent($button, $content);
            }
        }); 
	})
};

// initialize app.adminLanguagesSyncAll object until the document is loaded
$(document).ready(app.adminLanguagesSyncAll.init());