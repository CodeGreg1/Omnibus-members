"use strict";

app.updateTaxRate = {};

app.updateTaxRate.config = {
    editBtn: '.btn-edit-tax-rate',
    modal: '#edit-tax-rate-modal',
    form: '#updateTaxRateForm',
    submit: '.btn-update-tax-rate'
};

app.updateTaxRate.getEditData = function(e) {
    let self = this;
    let id = $(e.target).data('id');
    let data = app.taxRateDatatable.table.findItem('id', id);
    if (data) {
        $(self.config.modal).modal('show');
        $(self.config.form).find('[name="id"]').val(data.id);
        $(self.config.form).find('[name="title"]').val(data.title);
        $(self.config.form).find('[name="description"]').val(data.description);
        $(self.config.form).find('[name="percentage"]').val(data.percentage);
        $(self.config.form).find('[name="inclusive"]').val(data.inclusive);
        $(self.config.form).find('[name="tax_type"]').val(data.tax_type);
        $(self.config.form).find('[name="edit-active"]').attr('checked', data.active ? true : false);
    }
};

app.updateTaxRate.clearModal = function() {
    $(this.config.form).find('[name="id"]').val('');
    $(this.config.form).find('[name="title"]').val('');
    $(this.config.form).find('[name="description"]').val('');
    $(this.config.form).find('[name="percentage"]').val('');
    $(this.config.form).find('[name="inclusive"]').val(1);
    $(this.config.form).find('[name="tax_type"]').val('vat');
    $(this.config.form).find('[name="edit-active"]').attr('checked', false);
};

app.updateTaxRate.data = function() {
    return $(this.config.form).serializeArray();
};

app.updateTaxRate.route = function(id) {
    return '/admin/tax-rates/'+id+'/update';
};

app.updateTaxRate.ajax = function(e) {
    let self = this;
    let button = $(e.target);
    let id = $(self.config.form).find('[name="id"]').val();

    $.ajax({
        type: 'PATCH',
        url: self.route(id),
        data: self.data(),
        beforeSend: function () {
            button.attr('disabled', true).addClass('disabled btn-progress');
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.closeModal();
                app.notify(response.message);
                if (app.taxRateDatatable.table) {
                    app.taxRateDatatable.table.refresh();
                } else {
                    if (response.data.location) {
                        window.location = window.location;
                    }
                }

            }, 500);
        },
        complete: function (xhr) {
            button.attr('disabled', false).removeClass('disabled btn-progress');
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};

app.updateTaxRate.init = function() {
    var self = this;
    $(document).delegate(this.config.editBtn, 'click', this.getEditData.bind(this));
    $(this.config.submit).on('click', this.ajax.bind(this));

    $("#edit-shipping-rate-modal").on("hidden.bs.modal", function () {
        self.clearModal();
    });
};

$(document).ready(app.updateTaxRate.init());
