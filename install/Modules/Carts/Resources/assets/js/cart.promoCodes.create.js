"use strict";

app.cartCreatePromoCode = {};

app.cartCreatePromoCode.config = {
    button: '#btn-create-coupon-promo-code',
    form: '#createCouponPromoCodeForm',
    modal: '#create-coupon-promo-code-modal',
};

app.cartCreatePromoCode.data = function() {
    var data = $(this.config.form).serializeArray();
    data.push({name: 'coupon_id', value: $(this.config.button).data('id')});

    var users = $(this.config.form).find('[data-ts-item]').map(function(i, item) {
        return parseInt(item.dataset.value);
    }).get();

    if (users.length) {
        data.push({
            name: 'users',
            value: users
        });
    }

    return data;
};

app.cartCreatePromoCode.route = function() {
    return '/admin/coupons/' + $('#Coupon_id').val() + '/promo-codes/create';
};

app.cartCreatePromoCode.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: $self.route(),
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.backButtonContent($button, $content);
            $($self.config.modal).modal('hide');

            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }

            setTimeout(() => {
                app.cartPromoCodeDatatable.table.refresh();
            },1000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(() => {
                app.backButtonContent($button, $content);
                app.formErrors($self.config.form, response.responseJSON, response.status);
                // Display error for permissions
                app.roles.groupError(response.responseJSON);
            }, 250);
        }
    });
};

app.cartCreatePromoCode.customSelect = function() {
    this.config.plans = new TomSelect('#users',{
		valueField: 'id',
		labelField: 'full_name',
		searchField: ['full_name', 'name', 'first_name', 'last_name', 'email', 'username'],
		load: function(query, callback) {
            var param = {
                page: 1,
                limit: 15,
                length: 15,
                start: 0,
                sortValue: 'created_at__desc',
                searchValue: query
            };

            var users = $('#Users').val();
            if (users) {
                param.notIn = users;
            }

			var url = '/admin/users/query/datatable?' + app.objectToUrlParams(param);
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
				return `<div class="py-2 d-flex">
							<div class="icon me-3">
								<img class="mr-3 rounded" width="35" src="${ item.avatar || '/upload/users/avatar.png' }" alt="User">
							</div>
							<div>
								<div class="mb-1">
									<strong>
										${ escape(item.full_name) }
									</strong>
								</div>
						 		<div class="description">${ escape(item.email) }</div>
							</div>
						</div>`;
			},
			item: function(item, escape) {
				return `<div class="py-1 px-2 d-flex">
							<div class="icon me-3 d-flex align-items-center justify-content-center">
								<img class="mr-2 rounded" width="20" src="${ item.avatar || '/upload/users/avatar.png' }" alt="User">
							</div>
							<div>
								<div>
									<strong>
										${ escape(item.full_name) }
									</strong>
								</div>
							</div>
						</div>`;
			}
		},
	});
};

app.cartCreatePromoCode.init = function() {
    if (window.TomSelect !== undefined && $('#users').length) {
        this.customSelect();
    }

    $(this.config.button).click(function() {
        $(this.config.form).submit();
    });
    $(this.config.form).on('submit', this.process.bind(this));
};

$(document).ready(app.cartCreatePromoCode.init());
