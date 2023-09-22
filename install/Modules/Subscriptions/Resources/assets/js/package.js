app.package = {};

app.package.config = {
    pricingList: '#package-prices',
    addPricing: '.btn-add-package-pricing',
    removePricing: '.btn-remove-package-pricing'
};

app.package.priceHtml = function(i) {
    var html = '<div class="accordion border-top pt-3" id="package-pricing-'+i+'" data-id="'+i+'">';
    html += '<div class="d-flex align-items-center justify-content-between">';
    html += '<div class="d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" role="button" data-toggle="collapse" data-target="#package-price-'+i+'" aria-expanded="true">';
    html += '<h6 class="price-title">'+app.trans('Pricing details')+'</h6><i class="fa fa-angle-down"></i></div>';
    html += '<button type="button" class="btn btn-sm btn-danger ml-3 btn-remove-package-pricing" data-id="'+i+'">';
    html += '<h6 class="mb-0">×</h6></button></div>';
    html += '<div class="accordion-body collapse show px-0 pb-0" id="package-price-'+i+'" data-parent="#package-prices" style="">';
    html += '<div class="form-row"><div class="form-group col-md-7">';
    html += '<label for="pricing-'+i+'-price">'+app.trans('Price')+'</label>';
    html += '<div class="input-group"><div class="input-group-prepend">';
    html += '<select class="custom-select" id="pricing-1-currency" name="pricing-1-currency" style="width:100px;">';
    for (let index = 0; index < currencies.length; index++) {
        const element = currencies[index];
        html += '<option value="'+element.code+'"';
        if (currency === element.code) {
            html += 'selected="true"';
        }
        html += '>'+element.code+'</option>';
    }
    html += '</select>';
    html += '</div>';
    html += '<input type="text" class="form-control" id="pricing-'+i+'-price" name="pricing-'+i+'-price" placeholder="0.00"></div>';
    html += '</div><div class="form-group col-md-5">';
    html += '<label for="pricing-'+i+'-compare-price">'+app.trans('Compare at price')+'</label>';
    html += '<div class="input-group">';
    html += '<input type="text" class="form-control" id="pricing-'+i+'-compare-price" name="pricing-'+i+'-compare-price" placeholder="0.00"></div>';
    html += '</div></div>';
    html += '<ul class="nav nav-pills" id="planTypeTab-'+i+'" role="tablist">';
    html += '<li class="nav-item">';
    html += '<a class="planType nav-link show active" data-toggle="tab" href="#pricing-'+i+'-recurring-payment" role="tab" aria-controls="pricing-'+i+'-recurring-payment" data-id="recurring" aria-selected="true">'+app.trans('Recurring')+'</a>';
    html += '</li><li class="nav-item">';
    html += '<a class="planType nav-link" data-toggle="tab" href="#pricing-'+i+'-onetime-payment" role="tab" aria-controls="pricing-'+i+'-onetime-payment" aria-selected="false" data-id="onetime">'+app.trans('One time')+'</a>';
    html += '</li></ul><div class="tab-content" id="planTypeTab-'+i+'">';
    html += '<div class="tab-pane fade active show pb-0" id="pricing-'+i+'-recurring-payment" role="tabpanel" aria-labelledby="pricing-'+i+'-recurring-payment">';
    html += '<div class="form-group">';
    html += '<label for="pricing-'+i+'-term">'+app.trans('Term')+'</label>';
    html += '<select class="form-control select2" id="pricing-'+i+'-term" name="pricing-'+i+'-term">';
    for (let index = 0; index < terms.length; index++) {
        const term = terms[index];
        html += '<option value="'+term.id+'">'+term.title+'</option>';
    }
    html += '</select></div>';
    html += '<div class="form-group mb-0">';
    html += '<label for="pricing-1-trial_days_count">'+app.trans('Free trial')+'</label>';
    html += '<div class="input-group">';
    html += '<input type="text" class="form-control" id="pricing-'+i+'-trial_days_count" name="pricing-'+i+'-trial_days_count" placeholder="0">';
    html += '<div class="input-group-append">';
    html += '<div class="input-group-text">'+app.trans('days')+'</div>';
    html += '</div></div></div></div>';
    html += '<div class="tab-pane fade" id="pricing-'+i+'-onetime-payment" role="tabpanel" aria-labelledby="pricing-'+i+'-onetime-payment">';
    html += '</div></div></div></div>';
    return html;
};

app.package.addPricing = function() {
    var ids = $(this.config.pricingList).find('.accordion').map(function() {return parseInt(this.dataset.id)}).get();
    var maxId = Math.max(...ids);
    $('.collapse').collapse('hide');
    $(this.config.pricingList).append(this.priceHtml(maxId+1));
    $(this.config.pricingList).find('.btn-remove-package-pricing.d-none').removeClass('d-none');
    app.initSelect2();
};

app.package.handleRemovePricing = function(e) {
    var button = $(e.target).closest('button');
    this.removePricing(button.data('id'));
};

app.package.removePricing = function(id) {
    if ($(this.config.pricingList).find('.accordion').length > 1) {
        $(this.config.pricingList).find('.accordion[data-id="'+id+'"]').remove();
    }

    if ($(this.config.pricingList).find('.btn-remove-package-pricing').length === 1) {
        $(this.config.pricingList).find('.btn-remove-package-pricing').addClass('d-none');
    }
};

app.package.toggleSectionMenu = function() {
    $('.package-sections a.nav-link').on('click', function() {
        $('.package-sections a.nav-link.active').removeClass('active');
        $(this).addClass('active');
    });
};

app.package.init = function() {
    $(this.config.addPricing).on('click', this.addPricing.bind(this));
    $(document).delegate(this.config.removePricing, 'click', this.handleRemovePricing.bind(this));
    app.package.toggleSectionMenu();
};

app.package.init();
