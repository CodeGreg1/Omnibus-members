"use strict";

// define app.ticketSubjectUpdate object
app.ticketSubjectUpdate = {};

// handle app.ticketSubjectUpdate object configuration
app.ticketSubjectUpdate.config = {
    form: '#edit-ticket-subject-form'
};

// handle app.ticketSubjectUpdate object ajax request
app.ticketSubjectUpdate.ajax = function() {
    $(app.ticketSubjectUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $route = $(app.ticketSubjectUpdate.config.form).attr('data-route');
        var $button = $(app.ticketSubjectUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: $route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.ticketSubjectUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
};

// initialize functions of app.ticketSubjectUpdate object
app.ticketSubjectUpdate.init = function() {
    app.ticketSubjectUpdate.ajax();

    $(".btn-edit-subject").on('click', function() {
        $('#edit-ticket-subject-modal').modal('show');
    });
};

// initialize app.ticketSubjectUpdate object until the document is loaded
$(document).ready(app.ticketSubjectUpdate.init());