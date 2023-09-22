"use strict";

app.packageExtraConditionUpdate = {};

app.packageExtraConditionUpdate.config = {
    button: '.btn-edit-package-extra-condition-item',
    submit: '#btn-update-package-extra-condition',
    modal: '#edit-package-extra-condition-modal',
    form: '#edit-package-extra-condition-form'
};

app.packageExtraConditionUpdate.getRoute = function(p, i) {
    return '/admin/subscriptions/packages/'+p+'/extra-conditions/'+i+'/update';
};

app.packageExtraConditionUpdate.data = function() {
    return $(this.config.form).serializeArray();
};

app.packageExtraConditionUpdate.ajax = function() {
    var self = this;
    var button = $(self.config.submit);
    var formSubmit = $(self.config.form).find(':submit');
    var content = button.html();
    var p = $(self.config.form).find('[name="package_id"]').val();
    var i = $(self.config.form).find('[name="id"]').val();

    $.ajax({
        type: 'PATCH',
        url: self.getRoute(p, i),
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

app.packageExtraConditionUpdate.openForm = function(e) {
    var button = $(e.target).closest('button');
    var item = app.packageExtraConditionDatatable.config.items.find(item => item.id === parseInt(button.data('id')));
    if (item) {
        $(this.config.modal).find('[name="id"]').val(item.id);
        $(this.config.modal).find('[name="package_id"]').val(item.package_id);
        $(this.config.modal).find('[name="name"]').val(item.name);
        $(this.config.modal).find('[name="description"]').val(item.description);
        $(this.config.modal).find('[name="shortcode"]').val(item.shortcode);
        $(this.config.modal).find('[name="value"]').val(item.value);
         $(this.config.modal).modal('show');
    }
};

app.packageExtraConditionUpdate.init = function() {
    var self = this;
    $(document).delegate(this.config.button, 'click', this.openForm.bind(this));

    $(document).delegate(this.config.submit, 'click', function () {
        self.ajax();
    });
};

$(document).delegate(app.packageExtraConditionUpdate.init());
