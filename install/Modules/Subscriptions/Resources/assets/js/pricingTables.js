"use strict";

app.pricingTable = {};

app.pricingTable.config = {
	packagesAccordion: '#pricing-table-packages',
	packagesAccordionItemRemove: '.btn-remove-pricing-table-item',
	featuredPackage: '#featured_package',
	plans: null,
	data: [],
};

app.pricingTable.priceAccordionItem = function(item) {
	return `<tr role="row" class="pricing-table-item" data-id="${ item.id }">
				<td class="pricing-table-item-drag-handle">
					<span>
						<i class="fas fa-arrows-alt drag-handle"></i>
					</span>
				</td>
				<td class="pricing-table-item-details">
					<div class="accordion">
						<div class="accordion-header collapsed" role="button" data-toggle="collapse" data-target="#panel-body-${ item.id }" aria-expanded="false">
							<div class="d-flex flex-column">
								<strong>${ item.package } </strong>
								<span>${ item.name }</span>
							</div>
						</div>
						<div class="accordion-body collapse px-0" id="panel-body-${ item.id }" data-parent="#pricing-table-packages" style="">
							<div class="custom-control custom-checkbox mb-2">
								<input type="checkbox" class="custom-control-input allow_promo_code" id="allow_promo_code_${ item.id }">
								<label class="custom-control-label" for="allow_promo_code_${ item.id }">
									<strong>${app.trans('Allow promotion codes')}</strong>
								</label>
							</div>
							<div class="form-group mb-2">
								<label>${app.trans('Button label')} <span class="text-muted text-sm">(${app.trans('Optional')})</span></label>
								<input type="text" class="form-control button_label">
								<small class="form-text text-muted form-help">${app.trans('Custom button label for frontend only')}</small>
							</div>
							<div class="form-group mb-2">
								<label>${app.trans('Button link')} <span class="text-muted text-sm">(${app.trans('Optional')})</span></label>
								<input type="text" class="form-control button_link">
								<small class="form-text text-muted form-help">${app.trans('Custom button link for frontend only')}</small>
							</div>
							<div class="form-group mb-2">
								<label>${app.trans('Confirm page message')} <span class="text-muted text-sm">(${app.trans('Optional')})</span></label>
								<textarea class="form-control confirm_page_message" id="confirm_page_message_${ item.id }"></textarea>
								<small class="form-text text-muted">
									${app.trans('Please note that custom messages aren\'t translated based on your customer\'s language.')}
								</small>
							</div>
						</div>
					</div>
				</td>
				<td class="pricing-table-item-remove-handle">
					<a href="javascript:void(0)" role="button" class="btn-remove-pricing-table-item">
						<i class="fas fa-times"></i>
					</a>
				</td>
			</tr>`;
};

app.pricingTable.packageSelect = function() {
	const self = this;
    this.config.plans = new TomSelect('#pricing-table-search-packages',{
		valueField: 'id',
		labelField: 'name',
		optgroupField: 'class',
		searchField: 'package',
		closeAfterSelect: true,
		preload: true,
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
			var url = '/admin/subscriptions/packages/datatable?' + app.objectToUrlParams(param) + '&' + paramQuery;
			fetch(url)
				.then(response => response.json())
				.then(json => {
					const options = [];
					for (let x = 0; x < json.data.length; x++) {
						const plan = json.data[x];
						for (let y = 0; y < plan.prices.length; y++) {
							const price = plan.prices[y];
							if (price.type === 'recurring') {
								var name = price.amount_display + ' every ';
								if (price.term.interval_count > 1) {
									name = name + price.term.interval_count + ' ' + price.term.interval + 's';
								} else {
									name = name + price.term.interval;
								}
							} else {
								var name = price.amount_display;
							}

							options.push({
								id: price.id,
								name: name,
								package: plan.name,
								class: plan.id
							});
						}
					}

					const optionGroups = json.data.map(function(item) {
						return {value: item.id, label: item.name, id: item.id, name: item.name};
					});

					callback(options, optionGroups);
				}).catch(()=>{
					callback();
				});
		}
	});
};

app.pricingTable.selecPackage = function() {
	const self = this;
	this.config.plans.on('change', function(items) {
		$(this.control).find('[data-ts-item]').remove();
		for (let index = 0; index < items.length; index++) {
			const itemId = items[index];
			if (!$(self.config.packagesAccordion).find('tr[data-id="'+itemId+'"]').length) {
				const item = this.options[itemId];
				if (item) {
					item.order = 1;
					$(self.config.packagesAccordion+' tbody').append(self.priceAccordionItem(item));
					self.config.data.push(item);
				}

				if(!$(self.config.featuredPackage + ' option').filter(function(){ return $(this).val() == item.class; }).length){
					$(self.config.featuredPackage).append($('<option>', {
						value: item.class,
						text: item.package
					}));
				}
			}
		}
	});
};

app.pricingTable.removePricingItem = function() {
	const self = this;
	$(document).delegate(this.config.packagesAccordionItemRemove, 'click', function() {
		const accordion = $(this).parents('tr[data-id]');
		if (accordion.length) {
			const id = String (accordion.data('id'));
			self.config.plans.items = self.config.plans.items.filter(itemId => itemId !== id);
			accordion.remove();
			self.config.plans.refreshOptions();
			self.config.data = self.config.data.filter(price => price.id !== parseInt(id));
			$(self.config.featuredPackage + ' option').map(function(i,option) {
				if (option.value && !self.config.data.find(item => item.class === parseInt(option.value))) {
					$(option).remove();
				}
			});
		}
	});
};

app.pricingTable.addressCollection = function() {
	var self = this;
	$(document).delegate('.collect_all_addresses_select', 'change', function() {
		if (this.checked) {
			$(this).parents('.accordion').find('.collect_all_addresses').removeClass('d-none');
		} else {
			$(this).parents('.accordion').find('.collect_all_addresses').addClass('d-none');
		}
	});
};

app.pricingTable.setFeatured = function() {
	var self = this;
	$(document).delegate('[name="featured"]', 'change', function() {
		if (this.checked) {
			const selected = this;
			$(self.config.packagesAccordion).find('[name="featured"]:checked').map(function() {
				if (this !== selected) {
					this.checked = false;
				}
			});
		}
	});
};

app.pricingTable.init = function() {
    if (window.TomSelect !== undefined && $('#pricing-table-search-packages').length) {
        this.packageSelect();
		this.selecPackage();
    }

	this.removePricingItem();
	this.addressCollection();
	this.setFeatured();
};

$(document).ready(app.pricingTable.init());
