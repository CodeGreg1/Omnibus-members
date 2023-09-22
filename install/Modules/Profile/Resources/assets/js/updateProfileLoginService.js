"use strict";

// define app.updateProfileLoginService object
app.updateProfileLoginService = {};

// handle app.updateProfileLoginService object configuration
app.updateProfileLoginService.config = {
    route: '/profile/disconnect-social'
};

// handle app.updateProfileLoginService object disconnecting social
app.updateProfileLoginService.socialDisconnect = function(button) {
    var self = this;
    var provider = button.attr('data-provider');
    var content = button.html();

    $.ajax({
        type: 'POST',
        url: self.config.route,
        data: { provider: provider},
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.updateProfileLoginService.socialDisconnectResponse(button, response);
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            app.notifyError(XMLHttpRequest.responseJSON);
        }
    });
};

// handle on social disconnect response
app.updateProfileLoginService.socialDisconnectResponse = function(button, response) {
    button.attr('data-route', response.data.route);
    app.notify(response.message);
    setTimeout(function() {
        button.find('.caption').text(response.data.caption);
    }, 500);
};

// handle on clicking provider and disconnect
app.updateProfileLoginService.provider = function() {
    var self = this;

    $( '.btn-disconnect-social' ).on('click', function() {
        var button = $(this);
        var route = button.attr('data-route');
        
        if ( route == "" ) {
            app.updateProfileLoginService.socialDisconnect( button );
        } else {
            app.redirect( route );
        }
        
    });
};

// initialize functions of app.updateProfileLoginService object
app.updateProfileLoginService.init = function() {
    this.provider();
};

// initialize app.updateProfileLoginService object until the document is loaded
$( document ).ready( app.updateProfileLoginService.init() );