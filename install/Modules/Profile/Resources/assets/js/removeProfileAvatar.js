"use strict";

// define app.removeProfileAvatar object
app.removeProfileAvatar = {};

// handle app.removeProfileAvatar object configuration
app.removeProfileAvatar.config = {
    route: '/profile/avatar/remove',
    button: '#btn-remove-photo',
};

// handle on appending avatar after we removing the profile picture
app.removeProfileAvatar.appendAvatar = function(path) {
    $('.profile-avatar').attr('src', path);
};

// handle on ajax for removing profile avatar
app.removeProfileAvatar.process = function() {

    var self = this;
    var button = $(self.config.button);
    var content = button.html();

    button.on('click', function() {
        $.ajax({
            type: 'POST',
            url: self.config.route,
            beforeSend: function () {
                app.buttonLoader(button);
            },
            success: function (response, textStatus, xhr) {
                app.removeProfileAvatar.appendAvatar(response.data.avatar);
                app.notify(response.message);
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

// initialize functions of app.removeProfileAvatar object
app.removeProfileAvatar.init = function() {
    this.process();
};

// initialize app.removeProfileAvatar object until the document is loaded
$(document).ready(app.removeProfileAvatar.init());