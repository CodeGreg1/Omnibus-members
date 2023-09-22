app.updatePackageFeature = {};

app.updatePackageFeature.config = {
    modal: '#edit-feature-modal',
    form: '#edit-feature-form',
    button: '#btn-update-feature',
    editBtn: '.btn-edit-feature',
    modal: '#edit-feature-modal'
};

app.updatePackageFeature.data = function() {
    return  $(this.config.form).serializeArray();
};

app.updatePackageFeature.ajax = function() {
    var self = this;
    var button = $(self.config.button);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();
    console.log($(self.config.form).data('route'))
    $.ajax({
        type: 'PATCH',
        url: $(self.config.form).data('route'),
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
            app.closeModal();
            $(self.config.modal).modal('hide');
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
            setTimeout(function() {
                 app.formErrors(self.config.form, response.responseJSON, response.status);
            }, 500);
        }
    });
};

app.updatePackageFeature.init = function() {
    var self = this;

    $(document).delegate(self.config.button, 'click', function () {
        self.ajax();
    });

    $(document).delegate(self.config.editBtn, 'click', function() {
        $(self.config.modal).find(self.config.form).data('route', this.dataset.route);
        $(self.config.modal).find('#title').val(this.dataset.title);
        $(self.config.modal).find('#description').val(this.dataset.description);
        $(self.config.modal).modal('show');
    });
};

$(document).ready(app.updatePackageFeature.init());
