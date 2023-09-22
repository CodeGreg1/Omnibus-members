app.createPackagePrice = {};

app.createPackagePrice.config = {
    button: '#btn-create-package-price',
    form: '#create-package-price-form',
    modal: '#create-package-price-modal'
};

app.createPackagePrice.getRoute = function() {
    return $(this.config.form).data('route');
};

app.createPackagePrice.data = function() {
    var data =  $(this.config.form).serializeArray();
    data.push({
        name: 'type',
        value: $(this.config.form).find('.planType.active').data('id')
    });

    return data;
};

app.createPackagePrice.ajax = function() {
    var self = this;
    var button = $(self.config.button);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();

    $.ajax({
        type: 'POST',
        url: self.getRoute(),
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
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.createPackagePrice.init = function() {
    var self = this;
    $(document).delegate(this.config.button, 'click', function () {
        self.ajax();
    });
};

$(document).ready(app.createPackagePrice.init());
