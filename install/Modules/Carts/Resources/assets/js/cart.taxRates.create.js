"use strict";

app.createTaxRate = {};

app.createTaxRate.config = {
    submit: '.btn-create-tax-rate',
    form: '#createTaxRateForm',
    route: '/admin/tax-rates/create'
};

app.createTaxRate.data = function() {
    return $(this.config.form).serializeArray();
};

app.createTaxRate.clearModal = function() {
    $(this.config.form).find('#title').val('');
    $(this.config.form).find('#description').val('');
    $(this.config.form).find('#percentage').val('');
    $(this.config.form).find('#inclusive').val('1');
    $(this.config.form).find('#tax_type').val('vat');
    $(this.config.form).find('#active').prop( "checked", false );
};

app.createTaxRate.ajax = function(e) {
    var self = this;
    var button = $(e.target);

    $.ajax({
        type: 'POST',
        url: self.config.route,
        data: self.data(),
        beforeSend: function () {
            button.attr('disabled', true).addClass('disabled btn-progress');
        },
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                app.closeModal();
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

app.createTaxRate.init = function() {
    var self = this;
    $(this.config.submit).on('click', this.ajax.bind(this));

    $("#create-tax-rate-modal").on("hidden.bs.modal", function () {
        self.clearModal();
    });
};

$(document).ready(app.createTaxRate.init());
