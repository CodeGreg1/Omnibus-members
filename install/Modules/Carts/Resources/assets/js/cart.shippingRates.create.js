"use strict";

app.createShippingRate = {};

app.createShippingRate.config = {
    submit: '.btn-create-shipping-rate',
    form: '#createShippingRateForm',
    route: '/admin/shipping-rates/create'
};

app.createShippingRate.data = function() {
    return $(this.config.form).serializeArray();
};

app.createShippingRate.ajax = function(e) {
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
                if (app.shippingRateDatatable.table) {
                    app.shippingRateDatatable.table.refresh();
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

app.createShippingRate.clearModal = function() {
    $(this.config.form).find('#title').val('');
    $(this.config.form).find('#price').val('');
    $(this.config.form).find('#active').prop( "checked", false );
};

app.createShippingRate.init = function() {
    var self = this;
    $(this.config.submit).on('click', this.ajax.bind(this));

    $("#create-shipping-rate-modal").on("hidden.bs.modal", function () {
        self.clearModal();
    });
};

$(document).ready(app.createShippingRate.init());
