"use strict";

// define app.usersCreate object
app.usersCreate = {};

// handle app.usersCreate object configuration
app.usersCreate.config = {
    form: '#user-create-form'
};

// handle getting app.usersCreate object form data
app.usersCreate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle getting route for creating user
app.usersCreate.route = function(id) {
    return $(this.config.form).data('action');
};

// handle send invitation checkbox event
app.usersCreate.sendInvitationCheckbox = function() {
    $('[name="send_invitation"]').on('click', function() {
        if(!$(this).is(':checked') && $('[name="password"]').val() == '') {
            $(this).prop('checked', true);
        }
    });

    $('[name="password"]').on('change', function() {
        if($(this).val() == '') {
            $('[name="send_invitation"]').prop('checked', true);
        }
    });
};

// handle app.usersCreate object ajax request
app.usersCreate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: app.usersCreate.route(),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(response.data.redirectTo);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            app.backButtonContent($button, $content);
        }
    }); 
};

// initialize functions of app.usersCreate object
app.usersCreate.init = function() {
    app.usersCreate.sendInvitationCheckbox();
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.usersCreate object until the document is loaded
$(document).ready(app.usersCreate.init());