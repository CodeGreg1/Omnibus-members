"use strict";

app.adminAffiliateUserDatatable = {};

app.adminAffiliateUserDatatable.config = {
    dpButton: '#admin-affiliate-action-dropdown',
    datatable: '#admin-affiliates-datatable',
    options: {
        src: '/admin/affiliates/users/datatable',
        resourceName: { singular: app.trans("affiliate"), plural: app.trans("affiliates") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: 'admin-affiliate-user-row',
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
                title: app.trans('Code'),
                key: 'code',
                classes: 'admin-affiliate-code-row'
            },
            {
                title: app.trans('Referrals'),
                key: 'referrals_count',
                classes: 'admin-affiliate-referrals-count-row',
                searchable: false,
                orderable: false,
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
                        if (row.approved) {
                            if (row.active) {
                                color = 'primary';
                                label = app.trans('Active');
                            } else {
                                color = 'warning';
                                label = app.trans('Disabled');
                            }
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
                classes: 'admin-affiliate-actions-row tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="admin-affiliate-action-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu" data-button="#admin-affiliate-action-dropdown">';
                        if (row.approved) {
                            if (row.active) {
                                html += '<a class="dropdown-item btn-admin-affiliate-disable" role="button" href="javascript:void(0)" data-id="'+row.id+'" data-user="'+row.user.full_name+'" data-route="/admin/affiliates/users/'+row.id+'/disable">'+app.trans('Disable affiliate')+'</a>';
                            } else {
                                html += '<a class="dropdown-item btn-admin-affiliate-enable" role="button" href="javascript:void(0)" data-id="'+row.id+'" data-user="'+row.user.full_name+'" data-route="/admin/affiliates/users/'+row.id+'/enable">'+app.trans('Enable affiliate')+'</a>';
                            }
                        } else {
                            html += '<a class="dropdown-item btn-admin-affiliate-approve" role="button" href="javascript:void(0)" data-id="'+row.id+'" data-user="'+row.user.full_name+'" data-route="/admin/affiliates/users/'+row.id+'/approve">'+app.trans('Approve affiliate')+'</a>';
                            if (!row.rejected_at) {
                                html += '<a class="dropdown-item btn-admin-affiliate-reject" role="button" href="javascript:void(0)" data-id="'+row.id+'" data-user="'+row.user.full_name+'" data-route="/admin/affiliates/users/'+row.id+'/reject">'+app.trans('Reject affiliate')+'</a>';
                            }
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
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No affiliates were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Pending'), value: 'Pending' },
                    { label: app.trans('Active'), value: 'Active' },
                    { label: app.trans('Rejected'), value: 'Rejected' },
                    { label: app.trans('Disabled'), value: 'Disabled' },
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
            { label: app.trans('Active'), filters: [{ key: 'status', value: 'Active' }] },
            { label: app.trans('Rejected'), filters: [{ key: 'status', value: 'Rejected' }] },
            { label: app.trans('Disabled'), filters: [{ key: 'status', value: 'Disabled' }] },
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

app.adminAffiliateUserDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminAffiliateUserDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminAffiliateUserDatatable.init = function() {
    this.initDatatable();
};

$(document).ready(app.adminAffiliateUserDatatable.init());
