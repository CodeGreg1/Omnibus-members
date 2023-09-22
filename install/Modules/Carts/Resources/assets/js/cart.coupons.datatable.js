"use strict";

app.cartCouponDatatable = {};

app.cartCouponDatatable.config = {
    datatable: '#coupons-datatable',
    options: {
        src: '/admin/coupons/datatable',
        resourceName: { singular: app.trans("coupon"), plural: app.trans("coupons") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'coupon-name-column',
                element: function(row) {
                    return $('<a href="/admin/coupons/'+row.id+'" class="fw-bolder">'+row.name+'</a>');
                }
            },
            {
                title: app.trans('Terms'),
                key: 'terms',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Promo codes'),
                key: 'promo_codes_count',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Redemptions'),
                key: 'redemptions',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var count = row.times_redeemed;
                    if (row.redeem_limit_count) {
                        count = row.times_redeemed + '/' + row.redeem_limit_count;
                    }
                    return $('<span>'+count+'</span>');
                }
            },
            {
                title: app.trans('Expires'),
                key: 'expires',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: '',
                key: 'actions',
                searchable: false,
                classes: 'menu-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/coupons/'+row.id+'/edit">'+app.trans('Edit coupon')+'</a>';
                        html += '<a class="dropdown-item text-danger btn-delete-coupon" data-route="/admin/coupons/'+row.id+'/delete" href="#">'+app.trans('Delete coupon')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'amount',
                key: 'amount',
                hidden: true
            },
            {
                title: 'redeem_limit_count',
                key: 'redeem_limit_count',
                hidden: true
            },
            {
                title: 'times_redeemed',
                key: 'times_redeemed',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No coupons were found!")
        },
        filterControl: [
            {
                key: 'type',
                title: app.trans('Type'),
                choices: [
                    { label: app.trans('Percentage discount'), value: 'percentage' },
                    { label: app.trans('Fixed amount discount'), value: 'fixed' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: true,
                value: ''
            }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: 'Latest' },
                { value: 'id__asc', label: 'Oldest' },
                { value: 'name__asc', label: 'Name ( A - Z )' },
                { value: 'name__desc', label: 'Name ( Z - A )' }
            ]
        },
        selectable: false
    }
};

app.cartCouponDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.cartCouponDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.cartCouponDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.cartCouponDatatable.init());
