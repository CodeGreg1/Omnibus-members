"use strict";

app.cartCouponSubscriptions = {};

app.cartCouponSubscriptions.getStatusHtml = function(row) {
    var color = 'secondary';
    switch(row.status) {
        case 'active':
            color = 'primary';
            break;
        case 'cancelled':
            color = 'warning';
            break;
        case 'ended':
            color = 'danger';
            break;
        case 'trialing':
            color = 'info';
            break;
        default:
            color = 'secondary';
    }
    var stat = row.status.charAt(0).toUpperCase() + row.status.slice(1);
    return $('<div class="badge badge-'+color+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+row.status_display+'">'+stat+'</div>');
};

app.cartCouponSubscriptions.config = {
    datatable: '#coupon-subscriptions-datatable',
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

app.cartCouponSubscriptions.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#coupon-subscriptions-datatable').length) {
        app.cartCouponSubscriptions.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.cartCouponSubscriptions.init = function() {
    this.initDatatable();
}

$(document).ready(app.cartCouponSubscriptions.init());
