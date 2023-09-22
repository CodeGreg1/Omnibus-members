"use strict";

app.adminAffiliateCommissionDatatable = {};

app.adminAffiliateCommissionDatatable.config = {
    dpButton: '#admin-affiliate-action-dropdown',
    datatable: '#admin-affiliate-commissions-datatable',
    options: {
        src: '/admin/affiliates/commissions/datatable',
        resourceName: { singular: app.trans("commission"), plural: app.trans("commissions") },
        columns: [
            {
                title: app.trans('Affiliate'),
                key: 'affiliate',
                classes: 'admin-commission-affiliate-row',
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
                title: app.trans('Referred user'),
                key: 'referred',
                classes: 'admin-commission-referred-row',
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
                key: 'withdrawable_amount',
                classes: 'admin-commission-withdrawable-amount-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'admin-commission-type-row'
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
                classes: 'admin-affiliate-actions-row tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="admin-affiliate-action-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu" data-button="#admin-affiliate-action-dropdown">';
                        html += '<a class="dropdown-item btn-admin-commission-view-details" role="button" href="javascript:void(0)" data-id="'+row.id+'">'+app.trans('View details')+'</a>';
                        if (row.status_label !== 'Withdrawable'
                            && row.status_label !== 'Completed'
                            && row.status_label !== 'Rejected'
                        ) {
                            html += '<a class="dropdown-item btn-admin-commission-approve" role="button" href="javascript:void(0)" data-route="/admin/affiliates/commissions/'+row.id+'/approve" data-name="'+row.affiliate.user.full_name+'" data-email="'+row.affiliate.user.email+'">'+app.trans('Approve now')+'</a>';
                        }

                        if (row.status_label !== 'Completed'
                            && row.status_label !== 'Rejected'
                        ) {
                            html += '<a class="dropdown-item btn-admin-commission-reject" role="button" href="javascript:void(0)" data-route="/admin/affiliates/commissions/'+row.id+'/reject" data-name="'+row.affiliate.user.full_name+'" data-email="'+row.affiliate.user.email+'">'+app.trans('Reject')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
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

app.adminAffiliateCommissionDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-admin-commission-view-details', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.adminAffiliateCommissionDatatable.table.findItem('id', parseInt(id));
        if (item) {
            var message = `<ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Affiliate')}</span>
                                <div class="d-flex flex-column">
                                    <strong class="lh-sm mb-1">${ item.affiliate.user.full_name }</strong>
                                    <span class="text-muted text-sm lh-sm">${ item.affiliate.user.email }</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Referred user')}</span>
                                <div class="d-flex flex-column">
                                    <strong class="lh-sm mb-1">${ item.user.full_name }</strong>
                                    <span class="text-muted text-sm lh-sm">${ item.user.email }</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Type')}</span>
                                <span>
                                    ${item.type}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Rate')}</span>
                                <span>
                                    ${item.rate}%
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Amount')}</span>
                                <span>
                                    ${item.withdrawable_amount}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Status')}</span>
                                <span>
                                    ${item.status_label}
                                </span>
                            </div>
                        </li>`;

            if (item.status === 'rejected' && item.rejected_reason) {
                message += `<li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Reject reason')}</span>
                                <span>
                                    ${item.rejected_reason}
                                </span>
                            </div>
                        </li>`;
            }

            message += '</ul>';

            var dialog = bootbox.dialog({
                title: app.trans('Commission details'),
                message: message,
                buttons: {
                    cancel: {
                        label: app.trans("Close"),
                        className: 'btn-danger'
                    }
                }
            });
        }
    });
};

app.adminAffiliateCommissionDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminAffiliateCommissionDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
        $(this.config.datatable).on('table:draw.finish', function(e, api) {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
};

app.adminAffiliateCommissionDatatable.init = function() {
    this.initDatatable();
    this.initShowDetails();
};

$(document).ready(app.adminAffiliateCommissionDatatable.init());
