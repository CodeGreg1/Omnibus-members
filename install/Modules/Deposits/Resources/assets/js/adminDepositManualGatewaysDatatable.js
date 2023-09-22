"use strict";

app.adminDepositManualGatewayDatatable = {};

// handle getting user avatar
app.adminDepositManualGatewayDatatable.avatar = function(avatar) {
    if (avatar === null) {
        return app.config.avatarDefault;
    }
    return avatar;
};

app.adminDepositManualGatewayDatatable.config = {
    datatable: '#admin-deposit-manual-gateways-datatable',
    route: '/admin/shipping-rates/delete',
    options: {
        src: '/admin/deposits/gateway/manual/datatable',
        resourceName: { singular: app.trans("manual gateway"), plural: app.trans("manual gateways") },
        columns: [
            {
                title: app.trans('Gateway'),
                key: 'gateway',
                classes: 'manual-gateway-name-row',
                searchable: false,
                element: function(row) {
                    return $(`<div class="d-flex align-items-center">
                                <div class="media mr-3">
                                    <img src="${row.logo}" alt="${row.name}" width="55" height="55" style="object-fit: cover; object-position: center;">
                                </div>
                                <span class="text-bold">${row.name}</span>
                            </div>`);
                }
            },
            {
                title: app.trans('Min Amount'),
                key: 'min_amount_display',
                classes: 'deposit-manual-method-min_amount-display-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Max Amount'),
                key: 'max_amount_display',
                classes: 'deposit-manual-method-max_amount-display-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Charge'),
                key: 'charge',
                classes: 'deposit-manual-method-charge-row',
                searchable: false,
                element: function(row) {
                    return $(`<span class="d-flex align-items-center">
                                ${row.charge_display} + ${row.percent_charge || 0}%
                            </span>`);
                }
            },
            {
                title: app.trans('Status'),
                key: 'active',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var color = 'secondary';
                    var label = 'Archived';
                    if (row.status) {
                        color = 'primary';
                        label = 'Active';
                    }
                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'manual-gateway-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/deposits/gateway/manual/'+row.id+'/edit" data-id="'+row.id+'">'+app.trans('Edit gateway')+'</a>';
                        if (row.status) {
                            html += '<a class="dropdown-item text-danger btn-disable-manual-gateway" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Disable gateway')+'</a>';
                        } else {
                            html += '<a class="dropdown-item btn-enable-manual-gateway" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Enable gateway')+'</a>';
                        }
                        html += '<a class="dropdown-item text-danger btn-delete-manual-gateway" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Delete gateway')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'name',
                key: 'name',
                hidden: true
            },
            {
                title: 'min_limit',
                key: 'min_limit',
                hidden: true
            },
            {
                title: 'max_limit',
                key: 'max_limit',
                hidden: true
            },
            {
                title: 'fixed_charge',
                key: 'fixed_charge',
                hidden: true
            },
            {
                title: 'percent_charge',
                key: 'percent_charge',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No manual gateways were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Active'), value: 'active' },
                    { label: app.trans('Archived'), value: 'archived' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: true,
                value: ''
            }
        ],
        selectable: false,
        sortControl: {
            value: 'created_at__desc',
            options: []
        },
        limit: 25
    }
};

app.adminDepositManualGatewayDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminDepositManualGatewayDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminDepositManualGatewayDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.adminDepositManualGatewayDatatable.init());
