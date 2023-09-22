app.createPackage = {};

app.createPackage.config = {
    button: '#btn-create-package',
    infoForm: '#package-information',
    form: '#package-form',
    route: '/admin/subscriptions/packages/create',
    priceTermToggler: '.recurring-term-checkbox',
    packageFeatureList: '#package-feature-reorder-list'
};

app.createPackage.data = function() {
    var data = {}, $self = this;
    $($self.config.infoForm).serializeArray().map(item => {
        data[item.name] = item.value;
    });

    data.prices = $(app.package.config.pricingList).find('.accordion').map((i, el) => {
        var id = el.dataset.id;
        var el = $(el);
        var trialDays = el.find('[name="pricing-'+id+'-trial_days_count"]').val();
        var type = el.find('.planType.active').data('id');

        return {
            package_term_id: type==='recurring' ? el.find('[name="pricing-'+id+'-term"]').val():null,
            price: el.find('[name="pricing-'+id+'-price"]').val(),
            compare_at_price: el.find('[name="pricing-'+id+'-compare-price"]').val(),
            trial_days_count: type==='recurring'?trialDays:0,
            currency: el.find('[name="pricing-'+id+'-currency"]').val(),
            type: type
        };
    }).get();

    var extra_conditions = app.packageExtraCondition.getData();
    if (extra_conditions.length) {
        data.extra_conditions = extra_conditions;
    }

    data.features = $($self.config.form).find('.package-feature:checked').map(function() {
        return this.dataset.id
    }).get();

    data.limits = $('.package-permission-module-select:checked').map(function(i, item) {
        var tr = $(item).parents('tr');
        var input = tr.find('.package-permission-limit');
        var select = tr.find('.package-permission-term');
        return {
            permission_id: parseInt(item.dataset.id),
            limit: input.val(),
            term: select.val()
        };
    })
    .get();

    return data;
};

app.createPackage.ajax = function() {
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
            app.backButtonContent(button, content);
            // Check check if form validation errors
            app.formErrors(self.config.infoForm, response.responseJSON, response.status);
        }
    });
};

app.createPackage.create = function() {
    var self = this;

    $(self.config.button).on('click', function() {
        self.ajax();
    });
};

app.createPackage.addFeature = function(feature) {
    if ($('#package-feature-reorder-list .no-feature-found').length) {
        $('#package-feature-reorder-list .no-feature-found').remove();
    }
    var html = '<li class="media align-items-center">';
    html += '<span class="package-feature-handle-column mr-4"><span data-id="'+feature.id+'" data-ordering="'+feature.ordering+'"><i class="fas fa-arrows-alt drag-handle ui-sortable-handle"></i></span></span>';
    html += '<div class="custom-control custom-checkbox">';
    html += '<input type="checkbox" class="custom-control-input package-feature" id="feature-'+feature.id+'" data-id="'+feature.id+'">';
    html += '<label class="custom-control-label" for="feature-'+feature.id+'"></label>';
    html += '</div><div class="media-body"><h6 class="media-title mb-0">'+feature.title+'</h6></div></li>';
    $(this.config.packageFeatureList).append(html);
    $( "#package-feature-reorder-list" ).sortable( "refresh" );
},

app.createPackage.init = function() {
    this.create();
    app.initSelect2();
};

$(document).ready(app.createPackage.init());
