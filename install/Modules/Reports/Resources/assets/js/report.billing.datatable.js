"use strict";

app.adminReportBillingDatatable = {};

app.adminReportBillingDatatable.getStatusHtml = function(row) {
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
        case 'past_due':
            color = 'warning';
            break;
        default:
            color = 'secondary';
    }

    var status = row.status.replace(/\_/g, ' ');
    var stat = status.charAt(0).toUpperCase() + status.slice(1);
    return $('<div class="badge badge-'+color+'" data-toggle="tooltip" data-placement="top" title="" data-original-title="'+row.status_display+'">'+stat+'</div>');
};

app.adminReportBillingDatatable.config = {
    datatable: {
        src: '/admin/reports/billing/datatable',
        resourceName: { singular: app.trans("subscription"), plural: app.trans("subscriptions") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: '',
                searchable: false,
                element: function(row) {
                    var html = '<p style="margin-bottom: 0;">';
                    html += '<span style="font-weight: bold;">'+row.subscribable.full_name+'</span><br>';
                    html += '<a href="/admin/subscriptions/'+row.id+'" class="fw-bolder">'+row.user+'</a>';
                    html += '</p>';

                    return $(html);
                }
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell w-50px',
                searchable: false,
                element: function(row) {
                    return app.adminReportBillingDatatable.getStatusHtml(row);
                }
            },
            {
                title: app.trans('Package'),
                key: 'package',
                classes: 'hidden md:table-cell',
                orderable: false,
                searchable: false,
                element: function(row) {
                    var html = '<div class="d-flex flex-column align-items-start">';
                    html += '<span class="mb-1">'+row.package+'</span>';
                    html += '<span class="free-billing-badge '+(row.price ? 'text-white bg-primary' : '')+'">';
                    if (!row.is_free) {
                        if (row.recurring) {
                            html += row.price + ' - ' + row.term_description;
                        } else {
                            html += row.price + ' - forever';
                        }
                    } else {
                        html += app.trans('Free billing');
                    }
                    html += '</span>';
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Type'),
                key: 'gateway',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    let type = 'Lifetime';
                    if (row.recurring) {
                        type = 'Recurring';
                    }
                    return $('<span>'+type+'</span>');
                }
            },
            {
                title: app.trans('Gateway'),
                key: 'gateway',
                classes: 'hidden md:table-cell',
                searchable: false,
                element: function(row) {
                    var label = row.gateway.charAt(0).toUpperCase() + row.gateway.slice(1);
                    var html = '<span>'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'hidden md:table-cell',
                searchable: false,
            },
            {
                title: 'created_at',
                key: 'created_at',
                classes: 'hidden md:table-cell',
                hidden: true
            },
            {
                title: 'user',
                key: 'subscribable.name',
                classes: 'hidden md:table-cell',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No subscriptions were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('All'), value: 'All' },
                    { label: app.trans('Active'), value: 'Active' },
                    // { label: app.trans('New'), value: 'New' },
                    { label: app.trans('Trialing'), value: 'Trialing' },
                    { label: app.trans('Cancelled'), value: 'Cancelled' },
                    { label: app.trans('Ended'), value: 'Ended' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: '',
                hidden: true
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [{ key: 'status', value: 'All' }] },
            // { label: app.trans('New'), filters: [{ key: 'status', value: 'New' }] },
            { label: app.trans('Trialing'), filters: [{ key: 'status', value: 'Trialing' }] },
            { label: app.trans('Active'), filters: [{ key: 'status', value: 'Active' }] },
            { label: app.trans('Cancelled'), filters: [{ key: 'status', value: 'Cancelled' }] },
            { label: app.trans('Ended'), filters: [{ key: 'status', value: 'Ended' }] }
        ],
        limit: 25,
        yajra: true,
        selectable: false,
        showSearchQuery: false,
        load: false,
    }
};

app.adminReportBillingDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#admin-billing-reports-datatable').length) {
        app.adminReportBillingDatatable.datatable = {};
        app.adminReportBillingDatatable.datatable = $('#admin-billing-reports-datatable').JsDataTable(app.adminReportBillingDatatable.config.datatable);
        $('#admin-billing-reports-datatable').on('table:draw.finish', function(e, api) {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
};

app.adminReportBillingDatatable.init()
