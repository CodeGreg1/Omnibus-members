app.createPackageFeature = {};

app.createPackageFeature.config = {
    modal: '#create-feature-modal',
    form: '#create-feature-form',
    button: '#btn-create-feature',
    route: '/admin/subscriptions/packages/features/create',
};

app.createPackageFeature.data = function() {
    return  $(this.config.form).serializeArray();
};

app.createPackageFeature.ajax = function() {
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
            app.notify(response.message);
            app.closeModal();
            $(self.config.modal).modal('hide');
            if (app.packageFeatureDatatable.tb) {
                app.packageFeatureDatatable.tb.refresh();
                self.clearForm();
            } else if ($(app.createPackage.config.packageFeatureList).length &&
            $('.package-create-feature-list').length) {

                app.createPackage.addFeature(response.data.feature);
                self.clearForm();
            } else {
                app.redirect(window.location.href);
            }
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            formSubmit.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            app.backButtonContent(button, content);
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.createPackageFeature.clearForm = function() {
    $(this.config.form).find('[name="title"]').val('');
    $(this.config.form).find('[name="description"]').val('');
};

app.createPackageFeature.init = function() {
    var self = this;

    $(document).delegate(self.config.button, 'click', function () {
        self.ajax();
    });
};

$(document).ready(app.createPackageFeature.init());
