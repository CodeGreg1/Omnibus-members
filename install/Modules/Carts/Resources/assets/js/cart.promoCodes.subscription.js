"use strict";

app.subscriptionPromoCodeRedemption = {};

app.subscriptionPromoCodeRedemption.config = {
    datatable: '#promo-code-subscriptions-datatable',
    options: {
        src: window.location.pathname + '/subscriptions',
        resourceName: { singular: app.trans("subscription"), plural: app.trans("subscriptions") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: '',
                searchable: false,
                element: function(row) {
                    return $('<a href="/admin/subscriptions/'+row.id+'" class="fw-bolder">'+row.user+'</a>');
                }
            },
            {
                title: app.trans('Package'),
                key: 'package',
                classes: 'hidden md:table-cell',
                element: function(row) {
                    var html = '<div class="d-flex flex-column align-items-start">';
                    html += '<span class="mb-1">'+row.package+'</span>';
                    html += '<span class="free-billing-badge '+(row.price ? 'text-white bg-primary' : '')+'">';
                    if (!row.is_free) {
                        html += row.price + ' - ' + row.term_description;
                    } else {
                        html += 'Free billing';
                    }
                    html += '</span>';
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell w-50px',
                searchable: false,
                element: function(row) {
                    return app.cartCouponSubscriptions.getStatusHtml(row);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No subscriptions were found!")
        },
        selectable: false
    }
};

app.subscriptionPromoCodeRedemption.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.subscriptionPromoCodeRedemption.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.subscriptionPromoCodeRedemption.init = function() {
    this.initDatatable();
}

$(document).ready(app.subscriptionPromoCodeRedemption.init());
