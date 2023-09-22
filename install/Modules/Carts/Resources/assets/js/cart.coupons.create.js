"use strict";

app.createCartCoupon = {};

app.createCartCoupon.config = {
    button: '#btn-create-coupon',
    form: '#createCouponForm',
    discountType: '.discountType',
    typePanel: '.typePanel',
    selectPlans: '.selectPlans',
    planSelection: '#planSelection',
    currentPlans: '#currentPlans',
    duration: '#duration',
    multipleDuration: '#multipleDuration',
    dateLimit: '#dateLimit',
    dateLimitPanel: '#dateLimitPanel',
    redeemLimit: '#redeemLimit',
    redeemLimitPanel: '#redeemLimitPanel',
    route: '/admin/coupons/create',
    plans: {},
};

app.createCartCoupon.toggleDiscountType = function() {
    var self = this;

    $(self.config.discountType).on('change', function() {
        $(self.config.typePanel).addClass('d-none');
        $('#'+this.value+'DiscountType').removeClass('d-none');
    });
};

app.createCartCoupon.customSelect = function() {
    this.config.plans = new TomSelect('#plans',{
		valueField: 'id',
		labelField: 'name',
		searchField: 'name',
		load: function(query, callback) {
            var param = {
                page: 1,
                limit: 15,
                length: 15,
                start: 0,
                sortValue: 'created_at__desc'
            };
            var packages = $('#Packages').val();
            if (packages) {
                param.notIn = packages;
            }

            var paramQuery = encodeURIComponent('columns[0][data]') + '=name&';
            paramQuery += encodeURIComponent('columns[0][name]') + '=name&';
            paramQuery += encodeURIComponent('columns[0][searchable]') + '=true&';
            paramQuery += encodeURIComponent('columns[0][orderable]') + '=false&';
            paramQuery += encodeURIComponent('columns[0][search][value]') + '='+query+'&';
            paramQuery += encodeURIComponent('columns[0][search][regex]') + '=false&';
			var url = '/admin/packages/datatable?' + app.objectToUrlParams(param) + '&' + paramQuery;
			fetch(url)
				.then(response => response.json())
				.then(json => {
					callback(json.data);
				}).catch(()=>{
                    console.log(4)
					callback();
				});

		},
		// custom rendering functions for options and items
		render: {
			option: function(item, escape) {
				return `<div class="py-2 d-flex w-full">
							<div class="icon me-3">
								<img class="mr-3 rounded" width="40" src="/themes/stisla/assets/img/products/product-4-50.png" alt="product">
							</div>
							<div>
								<div class="mb-1">
									<strong>
										${ escape(item.name) }
									</strong>
								</div>
						 		<div class="description">${ escape(item.prices.length) } price</div>
							</div>
						</div>`;
			},
			item: function(item, escape) {
				return `<div class="py-2 d-flex">
							<div class="icon me-3">
								<img class="mr-3 rounded" width="25" src="/themes/stisla/assets/img/products/product-4-50.png" alt="product">
							</div>
							<div>
								<div>
									<strong>
										${ escape(item.name) }
									</strong>
								</div>
							</div>
						</div>`;
			}
		},
	});
};

app.createCartCoupon.selectPlans = function() {
    var self = this;
    $(self.config.selectPlans).on('change', function() {
        $(self.config.planSelection).toggleClass('d-none', !this.checked);
        self.config.plans.clear();
        self.config.plans.clearOptions();
    });
};

app.createCartCoupon.durationChange = function() {
    var self = this;
    $(self.config.duration).on('change', function() {
        $(self.config.multipleDuration).toggleClass('d-none', this.value !== 'custom');
    });
};

app.createCartCoupon.dateLimitCheck = function() {
    var self = this;

    $(self.config.dateLimit).on('change', function() {
        $(self.config.dateLimitPanel).toggleClass('d-none', !this.checked);
    });
};

app.createCartCoupon.redeemLimitCheck = function() {
    var self = this;

    $(self.config.redeemLimit).on('change', function() {
        $(self.config.redeemLimitPanel).toggleClass('d-none', !this.checked);
    });
};

app.createCartCoupon.data = function() {
    var data = $(this.config.form).serializeArray();
    var plans = $(this.config.form).find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    if (plans.length) {
        data.push({
            name: 'plans',
            value: plans
        });
    }

    return data;
};

app.createCartCoupon.ajax = function() {
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
            setTimeout(function() {
                app.notify(response.message);
                if (response.data.redirectTo) {
                    app.redirect(response.data.redirectTo);
                }
            }, 500);
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

app.createCartCoupon.submitForm = function() {
    var self = this;
    $(self.config.button).on('click', self.ajax.bind(self));
};

app.createCartCoupon.init = function() {
    if (window.TomSelect !== undefined && $('#plans').length) {
        this.customSelect();
    }
    this.toggleDiscountType();
    this.selectPlans();
    this.durationChange();
    this.dateLimitCheck();
    this.redeemLimitCheck();
    this.submitForm();

    if(jQuery().daterangepicker) {
        if($("#redeem_until").length) {
            $('#redeem_until').daterangepicker({
                locale: {format: 'YYYY-MM-DD HH:mm'},
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            });
        }
    }
}

$(document).ready(app.createCartCoupon.init());
