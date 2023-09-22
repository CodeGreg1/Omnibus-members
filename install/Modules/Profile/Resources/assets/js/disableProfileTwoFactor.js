"use strict";

// define app.disableProfileTwoFactor object
app.disableProfileTwoFactor = {};

// handle app.disableProfileTwoFactor object configuration
app.disableProfileTwoFactor.config = {
    form: '#profile-two-factor-form',
    button: '#btn-disable-2fa',
    route: '/profile/two-factor/disable',
    redirect: '/profile/security'
};

// handle getting app.disableProfileTwoFactor object form data
app.disableProfileTwoFactor.data = function() {
    return $(this.config.form).serializeArray();
};

// handle updating content for 2fa element
app.disableProfileTwoFactor.updateContent = function(content) {
    $('.2fa-content').html(content);
};

// handle app.disableProfileTwoFactor object ajax request
app.disableProfileTwoFactor.ajax = function() {
    var self = this;
    var button = $(self.config.button);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();

    $.ajax({
        type: 'POST',
        url: self.config.route,
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(self.config.redirect);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
            app.backButtonContent(button, content);
            formSubmit.attr('disabled', false);
        }
    });
};

// handle ajax request that disabling two factor
app.disableProfileTwoFactor.process = function() {

    var self = this;

    $(document).delegate(self.config.button, 'click', function () {
        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("Disabling two factor also remove exta layer of security for your account!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                    app.disableProfileTwoFactor.ajax();
                }
            }
        });
    });
};

// initialize functions of app.disableProfileTwoFactor object
app.disableProfileTwoFactor.init = function() {
    this.process();
};

// initialize app.disableProfileTwoFactor object until the document is loaded
$(document).ready(app.disableProfileTwoFactor.init());