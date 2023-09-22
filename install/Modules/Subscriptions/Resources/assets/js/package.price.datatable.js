app.packagePriceDatatable = {};

app.packagePriceDatatable.config = {
    pricesContainer: '#package-prices-list',
    pricesTable: '#package-prices-table',
    remove: '.btn-delete-package-price',
    noPriceHtml: '<div class="w-100 d-flex d-flex justify-content-center align-items-center" style="height: 200px;"><div class="d-flex flex-column align-items-center"><i class="fas fa-tags mb-1" style="font-size: 20px;"></i><p style="font-size: 16px;margin-bottom:0;">'+app.trans('No prices yet')+'</p></div></div>',
    editPackagePricingModal: '#edit-package-pricing-modal',
    editPackagePricingForm: '#update-package-pricing-form',
    prices: [],
}

app.packagePriceDatatable.getRoute = function() {
    var id = $(this.config.pricesContainer).data('id');
    return '/admin/subscriptions/packages/' + id + '/prices/datatable';
};

app.packagePriceDatatable.loader = function(t = false) {
    var html = '<div class="w-100" style="height: 200px;">';
    html += '<div class="h-100 d-flex justify-content-center align-items-center">';
    html += '<a href="#" class="btn disabled btn-secondary btn-progress">'+app.trans('Progress')+'</a>';
    html += '</div></div>';
    return html;
};

app.packagePriceDatatable.loadTable = function(prices) {
    this.config.prices = prices;
    var container = $(this.config.pricesContainer);
    container.html('');
    if (!prices.length) {
        container.html(this.config.noPriceHtml);
        return;
    }
    app.package.config.prices = prices;
    var table = $('<table class="table mb-0">');
    container.append(table);
    table.append('<thead><tr><th>'+app.trans('Price')+'</th><th>'+app.trans('Created')+'</th><th>'+app.trans('Subscriptions')+'</th><th style="width: 80px;"></th></tr></thead><tbody></tbody>');
    for (let index = 0; index < prices.length; index++) {
        const price = prices[index];
        var tr = $('<tr>');
        var priceDesc = price.amount_display;
        if (price.type === 'recurring') {
            priceDesc += ' / ';
            if (price.term.interval_count > 1) {
                priceDesc += price.term.interval_count + ' ' + price.term.interval + 's';
            } else {
                priceDesc += price.term.interval;
            }
        } else {
            priceDesc += ' forever';
        }

        tr.append('<td><div class="d-flex"><a href="/admin/subscriptions/packages/'+price.package_id+'/prices/'+ price.id +'"><strong>'+priceDesc+'</strong></a></div></td>');
        tr.append('<td>'+price.created+'</td>');
        tr.append('<td>'+price.subscriptions_count+' active</td>');
        tr.append('<td><div class="d-flex"><button type="button" class="btn btn-icon text-danger btn-delete-package-price '+(price.editable ? '' : 'disabled')+'" data-route="/admin/subscriptions/packages/'+price.package_id+'/prices/'+price.id+'/delete" '+(price.editable ? '' : 'disabled="true"')+'><i class="fas fa-trash-alt"></i></button></div></td>');
        table.find('tbody').append(tr);
    }
};

app.packagePriceDatatable.getPrices = function() {
    var self = this;

    $.ajax({
        type: 'GET',
        url: self.getRoute(),
        beforeSend: function () {
            if ($(self.config.pricesTable).length) {
                $(self.config.pricesContainer).append(self.loader());
            } else {
                $(self.config.pricesContainer).html(self.loader(true));
            }
        },
        success: function (response, textStatus, xhr) {
            self.loadTable(response.data);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            $(self.config.pricesContainer).html('');
        }
    });
};

app.packagePriceDatatable.delete = function() {
    var self = this;
    $(document).delegate(self.config.remove, 'click', function() {
        var button = $(this);
        var price = self.config.prices.find(item => item.id == button.data('id'));

        if (price) {
            bootbox.confirm({
                title: app.trans("Are you sure?"),
                message: app.trans("Your about to remove package price."),
                buttons: {
                    confirm: {
                        label: app.trans('Yes'),
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: app.trans('No'),
                        className: 'btn-default'
                    }
                },
                callback: function (result) {
                    if ( result ) {
                        var dialogRemovePackagePriceGateway = bootbox.dialog({
                            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                            closeButton: false
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: '/admin/subscriptions/packages/'+price.package_id+'/prices/' + price.id,
                            success: function (response, textStatus, xhr) {
                                app.notify(response.message);
                                self.getPrices();
                                dialogRemovePackagePriceGateway.modal('hide');
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                var response = XMLHttpRequest;
                                dialogRemovePackagePriceGateway.modal('hide');
                                // Check check if form validation errors
                            }
                        });
                    }
                }
            });
        }
    });
};

app.packagePriceDatatable.init = function() {
    if ($(this.config.pricesContainer).length) {
        this.getPrices();
    }

    this.delete();
}

$(document).ready(app.packagePriceDatatable.init());
