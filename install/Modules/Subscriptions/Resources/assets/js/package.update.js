app.updatePackage = {};

app.updatePackage.config = {
    button: '#btn-update-package',
    form: '#edit-package-form'
};

app.updatePackage.data = function() {
    return  $(this.config.form).serializeArray();
};

app.updatePackage.ajax = function() {
    var self = this;
    var button = $(self.config.button);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();
    var route = $(self.config.form).data('route');

    $.ajax({
        type: 'PATCH',
        url: route,
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            formSubmit.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.updatePackage.init = function() {
    var self = this;

    $(document).delegate(self.config.button, 'click', function () {
        self.ajax();
    });
};

$(document).ready(app.updatePackage.init());
