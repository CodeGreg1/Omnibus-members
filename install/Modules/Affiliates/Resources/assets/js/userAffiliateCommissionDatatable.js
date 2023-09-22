"use strict";

app.userAffiliateCommissionDatatable = {};

app.userAffiliateCommissionDatatable.config = {
    datatable: '#user-affiliate-commissions-datatable',
    options: {
        src: '/user/commissions/datatable',
        resourceName: { singular: app.trans("commission"), plural: app.trans("commissions") },
        columns: [
            {
                title: app.trans('Referred user'),
                key: 'referred',
                classes: 'user-commission-referred-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <strong>${ row.user }</strong>
                            <span class="text-muted text-sm">${ row.user_email }</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('Amount'),
                key: 'withdrawable_amount',
                classes: 'user-commission-withdrawable-amount-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'user-commission-type-row'
            },
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'user-commission-date-row'
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    if (row.status_label === 'Rejected' && row.rejected_reason) {
                        return $('<span class="badge badge-'+row.status_color+'" data-toggle="tooltip" data-original-title="'+row.rejected_reason+'">'+row.status_label+'</span>');
                    }
                    return $('<span class="badge badge-'+row.status_color+'">'+row.status_label+'</span>');
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'user-commission-actions-row tb-actions-column text-right',
                element: function(row) {
                    if (row.status_label === 'Withdrawable') {
                        return $(`<button type="button" class="btn btn-primary btn-sm btn-commission-withdraw" data-route="/user/commissions/${row.id}/withdraw"><i class="fas fa-money-bill"></i></button>`);
                    } else {
                        return $(`<span class="text-muted">--</span>`);
                    }
                }
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
            {
                title: 'amount',
                key: 'amount',
                hidden: true
            },
            {
                title: 'rate',
                key: 'rate',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No commissions were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('For verification'), value: 'For verification' },
                    { label: app.trans('Withdrawable'), value: 'Withdrawable' },
                    { label: app.trans('Completed'), value: 'Completed' },
                    { label: app.trans('Rejected'), value: 'Rejected' }
                ],
                hidden: true,
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [] },
            { label: app.trans('For verification'), filters: [{ key: 'status', value: 'For verification' }] },
            { label: app.trans('Withdrawable'), filters: [{ key: 'status', value: 'Withdrawable' }] },
            { label: app.trans('Completed'), filters: [{ key: 'status', value: 'Completed' }] },
            { label: app.trans('Rejected'), filters: [{ key: 'status', value: 'Rejected' }] }
        ],
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

app.userAffiliateCommissionDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.userAffiliateCommissionDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);

        $(this.config.datatable).on('table:draw.finish', function(e, api) {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
};

app.userAffiliateCommissionDatatable.init = function() {
    this.initDatatable();
};

$(document).ready(app.userAffiliateCommissionDatatable.init());
