"use strict";

app.packageExtraConditionCreate = {};

app.packageExtraConditionCreate.config = {
    button: '#btn-create-package-extra-condition',
    form: '#create-package-extra-condition-form',
    modal: '#create-package-extra-condition-modal'
};

app.packageExtraConditionCreate.getRoute = function() {
    return $(this.config.form).data('route');
};

app.packageExtraConditionCreate.data = function() {
    var data =  $(this.config.form).serializeArray();
    data.push({
        name: 'type',
        value: $(this.config.form).find('.planType.active').data('id')
    });

    return data;
};

app.packageExtraConditionCreate.ajax = function() {
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
            app.packageExtraConditionDatatable.getItems();
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

app.packageExtraConditionCreate.clearModal = function() {
    $(this.config.form).find('#name').val('');
    $(this.config.form).find('#description').val('');
    $(this.config.form).find('#shortcode').val('');
    $(this.config.form).find('#value').val('');
};

app.packageExtraConditionCreate.init = function() {
    var self = this;
    $(document).delegate(this.config.button, 'click', function () {
        self.ajax();
    });

    $(self.config.modal).on("hidden.bs.modal", function () {
        self.clearModal();
    });
};

$(document).ready(app.packageExtraConditionCreate.init());
