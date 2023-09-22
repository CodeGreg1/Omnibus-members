"use strict";

app.userCreateAffiliateUser = {};

app.userCreateAffiliateUser.config = {
    btn: '#btn-create-affiliate-user',
    route: '/user/affiliates/create'
};

app.userCreateAffiliateUser.ajax = function() {
    const self = this;
    $(app.userCreateAffiliateUser.config.btn).on('click', function(e) {
        e.preventDefault();
        var $button = $(app.userCreateAffiliateUser.config.btn);
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: app.userCreateAffiliateUser.config.route,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
                $button.addClass('disabled').prop('disabled', true);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
                app.notify(response.message);
                $button.removeClass('disabled').prop('disabled', false);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.notify(response.responseJSON.message);
                // Reset button
                app.backButtonContent($button, $content);
                $button.removeClass('disabled').prop('disabled', false);
            }
        });
    });
};

app.userCreateAffiliateUser.init = function() {
    this.ajax();
};

$(document).ready(app.userCreateAffiliateUser.init());
