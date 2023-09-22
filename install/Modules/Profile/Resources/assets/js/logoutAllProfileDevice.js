"use strict";

// define app.logoutAllProfileDevice object
app.logoutAllProfileDevice = {};

// handle app.logoutAllProfileDevice object configuration
app.logoutAllProfileDevice.config = {
    button: '#logout-all-devices',
    route: '/profile/all-device/logout'
};

// handle redirection after logout all device
app.logoutAllProfileDevice.handleRedirect = function(redirectTo) {
    setTimeout(function() {
        app.redirect(redirectTo);
    }, 1000);
};

// handle removing element when we logout the device
app.logoutAllProfileDevice.handleRemovingElement = function() {
    $.each($('.list-group-sessions .list-group-item'), function() {
        $(this).remove();
    });
};

// handle ajax request for logout all profile device sessions
app.logoutAllProfileDevice.init = function() {
    var self = this;

    $(this.config.button).on('click', function() {
        var button = $(this);
        var content = button.html();

        $.ajax({
            type: 'POST',
            url: self.config.route,
            beforeSend: function () {
                app.buttonLoader(button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                
                app.logoutAllProfileDevice.handleRemovingElement();

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

// initialize app.logoutAllProfileDevice object until the document is loaded
$(document).ready(app.logoutAllProfileDevice.init());