"use strict";

app.adminDepositRequestsDatatable = {};

app.adminDepositRequestsDatatable.config = {
    datatable: '#admin-deposit-requests-datatable',
    route: '/admin/deposits/datatable',
    options: {
        src: '/admin/deposits/datatable',
        resourceName: { singular: app.trans("deposit request"), plural: app.trans("deposit requests") },
        columns: [
            {
                title: app.trans('Gateway'),
                key: 'trx',
                classes: 'deposit-history-trx-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    if (row.gateway) {
                        var gateway = row.gateway.charAt(0).toUpperCase() + row.gateway.slice(1);
                    } else {
                        var gateway = row.method.name;
                    }

                    return $(`
                        <div class="d-flex flex-column">
                            <strong>${ gateway }</strong>
                            <span class="text-muted text-sm">${ row.trx }</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('User'),
                key: 'user',
                classes: 'deposit-history-user-row',
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
                title: app.trans('Amount'),
                key: 'total',
                classes: 'deposit-history-total-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Charge'),
                key: 'charge_display',
                classes: 'deposit-history-total-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <span>${ row.charge_display }</span>
                            <span class="text-sm text-muted">${ row.fixed_charge_display } + ${row.percent_charge_rate}%</span>
                        </div>
                    `);
                }
            },
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'deposit-history-date-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Status'),
                key: 'active',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var color = 'secondary';
                    var label = app.trans('Pending');
                    if (row.rejected_at) {
                        color = 'danger';
                        label = app.trans('Rejected');
                    } else {
                        if (row.status) {
                            color = 'primary';
                            label = app.trans('Completed');
                        }
                    }

                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'deposit-actions-column tb-actions-column text-right',
                element: function(row) {
                    return $(`<a href="/admin/deposits/${row.trx}" class="btn btn-primary btn-sm ${row.method_id ? '' : 'disabled'}" ${row.method_id ? '' : 'disabled="disabled"'}><i class="fas fa-eye"></i></a>`);
                }
            },
            {
                title: 'trx',
                key: 'trx',
                hidden: true
            },
            {
                title: 'gateway',
                key: 'gateway',
                hidden: true
            },
            {
                title: 'amount',
                key: 'amount',
                hidden: true
            },
            {
                title: 'fixed_charge',
                key: 'fixed_charge',
                hidden: true
            },
            {
                title: 'percent_charge_rate',
                key: 'percent_charge_rate',
                hidden: true
            },
            {
                title: 'percent_charge',
                key: 'percent_charge',
                hidden: true
            },
            {
                title: 'charge',
                key: 'charge',
                hidden: true
            },
            {
                title: 'currency',
                key: 'currency',
                hidden: true
            },
            {
                title: 'first_name',
                key: 'user.first_name',
                hidden: true
            },
            {
                title: 'last_name',
                key: 'user.last_name',
                hidden: true
            },
            {
                title: 'email',
                key: 'user.email',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No requests were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Pending'), value: 'Pending' },
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
            { label: app.trans('Pending'), filters: [{ key: 'status', value: 'Pending' }] },
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

app.adminDepositRequestsDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminDepositRequestsDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminDepositRequestsDatatable.init = function() {
    this.initDatatable();
};

$(document).ready(app.adminDepositRequestsDatatable.init());
