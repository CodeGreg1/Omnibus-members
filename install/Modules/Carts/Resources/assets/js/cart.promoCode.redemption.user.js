"use strict";

app.userPromoCodeRedemption = {};

app.userPromoCodeRedemption.config = {
    datatable: '#promo-code-redemptions-users-datatable',
    options: {
        src: window.location.pathname + '/user-redemptions',
        resourceName: { singular: app.trans("user"), plural: app.trans("users") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'coupon-name-column',
                element: function(row) {
                    var html = '<div class="d-flex flex-column"><span><strong>'+row.full_name+'</strong></span>';
                    html += '<span class="text-muted">'+row.email+'</span></html>';
                    return $(html);
                }
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Last login'),
                key: 'last_login_for_humans',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No users were found!")
        },
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
    }
};

app.userPromoCodeRedemption.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.userPromoCodeRedemption.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.userPromoCodeRedemption.init = function() {
    this.initDatatable();
}

$(document).ready(app.userPromoCodeRedemption.init());
