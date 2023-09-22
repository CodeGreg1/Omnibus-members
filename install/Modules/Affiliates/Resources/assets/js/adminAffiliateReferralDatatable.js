"user strict";

app.adminAffiliateReferralDatatable = {};

app.adminAffiliateReferralDatatable.config = {
    dpButton: '#admin-affiliate-referral-action-dropdown',
    datatable: '#admin-affiliate-referrals-datatable',
    options: {
        src: '/admin/affiliates/referrals/datatable',
        resourceName: { singular: app.trans("affiliate referral"), plural: app.trans("affiliate referrals") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: 'admin-affiliate-referral-user-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <strong>${ row.user.full_name }</strong>
                            <span class="text-muted text-sm">${ row.user.email }</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('Referred by'),
                key: 'referred_by',
                classes: 'admin-affiliate-referral-referred-by-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <strong>${ row.affiliate.user.full_name }</strong>
                            <span class="text-muted text-sm">${ row.affiliate.user.email }</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'admin-affiliate-referral-created-row md:table-cell'
            },
            {
                title: 'user',
                key: 'user.first_name',
                hidden: true
            },
            {
                title: 'user',
                key: 'user.last_name',
                hidden: true
            },
            {
                title: 'user',
                key: 'user.email',
                hidden: true
            },
            {
                title: 'affiliate',
                key: 'affiliate.user.first_name',
                hidden: true
            },
            {
                title: 'affiliate',
                key: 'affiliate.user.last_name',
                hidden: true
            },
            {
                title: 'affiliate',
                key: 'affiliate.user.email',
                hidden: true
            },
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No affiliate referrals were found!")
        },
        selectable: false,
        sortControl: {
            value: 'created_at__desc',
            options: [
                { value: 'created_at__desc', label: app.trans('Latest Created') },
                { value: 'created_at__asc', label: app.trans('Oldest Created') }
            ]
        },
        limit: 25
    }
};

app.adminAffiliateReferralDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminAffiliateReferralDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminAffiliateReferralDatatable.init = function() {
    this.initDatatable();
};

$(document).ready(app.adminAffiliateReferralDatatable.init());
