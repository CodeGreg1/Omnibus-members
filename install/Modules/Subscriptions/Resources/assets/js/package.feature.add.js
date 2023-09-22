app.addPackageFeature = {};

app.addPackageFeature.config = {
    form: '#save-new-package-features-form'
};

app.addPackageFeature.data = function() {
    var features = $(this.config.form).find('[name="package-feature"]:checked').map(function(i, item) {
        return $(item).data('id');
    }).get();

    return { features };
};

app.addPackageFeature.ajax = function(e) {
    e.preventDefault();

    var self = this;
    var formSubmit = $(self.config.form).find(':submit');
    var route = $(self.config.form).data('href');
    var content = formSubmit.html();

    $.ajax({
        type: 'POST',
        url: route,
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(formSubmit);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function (xhr) {
            app.backButtonContent(formSubmit, content);
            formSubmit.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(function() {
                app.formErrors(self.config.form, response.responseJSON, response.status);
            }, 200);
        }
    });
};

app.addPackageFeature.init = function() {
    $(this.config.form).on('submit', this.ajax.bind(this));
};

$(document).ready(app.addPackageFeature.init());
