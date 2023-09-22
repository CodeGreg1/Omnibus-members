"use strict";

// define app.logoutProfileDevice object
app.logoutProfileDevice = {};

// handle app.logoutProfileDevice object configuration
app.logoutProfileDevice.config = {
    button: '.device-logout',
    route: '/profile/device/logout'
};

// handle redirection after logout device
app.logoutProfileDevice.handleRedirect = function(redirectTo) {
    setTimeout(function() {
        app.redirect(redirectTo);
    }, 1000);
};

// handle removing element when we logout the device
app.logoutProfileDevice.handleRemovingElement = function(sessionId) {
    $.each($('.list-group-sessions .list-group-item'), function() {
        if ( $(this).attr('data-id') == sessionId ) {
            $(this).remove();
        }
    });
};

// handle ajax request for logout profile device session
app.logoutProfileDevice.init = function() {
    var self = this;

    $(this.config.button).on('click', function() {
        var button = $(this);
        var content = button.html();
        var sessionId = button.parents('.list-group-item').data('id');

        $.ajax({
            type: 'POST',
            url: self.config.route,
            data: { id:sessionId },
            beforeSend: function () {
                app.buttonLoader(button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                
                app.logoutProfileDevice.handleRemovingElement(response.data.session_id);

                if ( response.data.redirectTo !== undefined ) {
                    self.handleRedirect(response.data.redirectTo);
                }
            },
            complete: function (xhr) {
                app.backButtonContent(button, content);
            },
            error: function (response) {
                app.notify(response.responseJSON.message);
            }
        }); 
    });
};

// initialize app.logoutProfileDevice object until the document is loaded
$(document).ready(app.logoutProfileDevice.init());