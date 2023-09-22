app.installWebhook = {};

app.installWebhook.config = {
    button: '.btn-install-gateway-webhook'
};

app.installWebhook.process = function(e) {
    var route = e.target.dataset.route;
    var self = this;
    var button = $(self.config.button);
    var content = button.html();

    $.ajax({
        type: 'GET',
        url: route,
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                app.closeModal();
                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            }, 400);
        },
        complete: function (xhr) {
            setTimeout(function() {
                app.backButtonContent(button, content);
            }, 400);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            setTimeout(function() {
                app.notify(response.responseJSON.message);
            }, 400);
        }
    });
};

app.installWebhook.init = function() {
    $(this.config.button).on('click', this.process.bind(this));
};

$(document).ready(app.installWebhook.init());
