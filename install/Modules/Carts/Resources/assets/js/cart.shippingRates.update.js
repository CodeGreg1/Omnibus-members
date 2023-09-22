"use strict";

app.updateShippingRate = {};

app.updateShippingRate.config = {
    editBtn: '.btn-edit-shipping-rate',
    modal: '#edit-shipping-rate-modal',
    form: '#updateShippingRateForm',
    submit: '.btn-update-shipping-rate'
};

app.updateShippingRate.getEditData = function(e) {
    let self = this;
    let id = $(e.target).data('id');
    let data = app.shippingRateDatatable.table.findItem('id', id);
    if (data) {
        $(self.config.modal).modal('show');
        $(self.config.form).find('[name="id"]').val(data.id);
        $(self.config.form).find('[name="title"]').val(data.title);
        $(self.config.form).find('[name="currency"]').val(data.currency);
        $(self.config.form).find('[name="price"]').val(data.price);
        $(this.config.form).find('[name="edit-active"]').attr('checked', data.active ? true : false);
    }
};

app.updateShippingRate.clearModal = function() {
    let defaultCurrency = $(this.config.form).find('[name="currency"] option:first').val();
    $(this.config.form).find('[name="id"]').val('');
    $(this.config.form).find('[name="title"]').val('');
    $(this.config.form).find('[name="currency"]').val(defaultCurrency);
    $(this.config.form).find('[name="price"]').val('');
    $(this.config.form).find('[name="edit-active"]').attr('checked', false);
};

app.updateShippingRate.data = function() {
    return $(this.config.form).serializeArray();
};

app.updateShippingRate.route = function(id) {
    return '/admin/shipping-rates/'+id+'/update';
};

app.updateShippingRate.ajax = function(e) {
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

app.updateShippingRate.init = function() {
    var self = this;
    $(document).delegate(this.config.editBtn, 'click', this.getEditData.bind(this));
    $(this.config.submit).on('click', this.ajax.bind(this));

    $("#edit-shipping-rate-modal").on("hidden.bs.modal", function () {
        self.clearModal();
    });
};

$(document).ready(app.updateShippingRate.init());
