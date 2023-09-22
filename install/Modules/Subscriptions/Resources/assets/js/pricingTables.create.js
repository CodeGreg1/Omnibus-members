"use strict";

app.createPricingTable = {};

app.createPricingTable.config = {
    button: '#btn-create-pricing-table',
    form: '#createPricingTableForm',
    route: '/admin/subscriptions/pricing-tables/create'
};

app.createPricingTable.data = function() {
    const data = {
        name: $(this.config.form).find('[name="name"]').val(),
        description: $(this.config.form).find('[name="description"]').val(),
        featured: $(this.config.form).find('[name="featured_package"]').val(),
        items: []
    };

    const accordionItems = $(app.pricingTable.config.packagesAccordion).find('tr.pricing-table-item').get();
    if (accordionItems.length) {
        for (let index = 0; index < accordionItems.length; index++) {
            const item = accordionItems[index];
            data.items.push({
                package_price_id: $(item).data('id'),
                allow_promo_code: $(item).find('.allow_promo_code:checked').length,
                confirm_page_message: $(item).find('.confirm_page_message').val(),
                button_label: $(item).find('.button_label').val(),
                button_link: $(item).find('.button_link').val()
            })
        }
    }

    return data;
};

app.createPricingTable.ajax = function() {
    const self = this;
    const button = $(self.config.button);
    const content = button.html();

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
            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors(self.config.form, response.responseJSON, response.status);
        }
    });
};


app.createPricingTable.init = function() {
    var self = this;
    $(document).delegate(this.config.button, 'click', function () {
        self.ajax();
    });
};

$(document).ready(app.createPricingTable.init());
