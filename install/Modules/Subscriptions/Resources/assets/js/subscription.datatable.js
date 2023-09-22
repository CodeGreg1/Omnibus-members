app.subscriptionDatatable = {};

app.subscriptionDatatable.getStatusHtml = function(row) {
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

app.subscriptionDatatable.config = {
    datatable: {
        src: '/admin/subscriptions/datatable',
        resourceName: { singular: app.trans("subscription"), plural: app.trans("subscriptions") },
        columns: [
            {
                title: app.trans('User'),
                key: 'user',
                classes: '',
                searchable: false,
                element: function(row) {
                    var html = '<p style="margin-bottom: 0;">';
                    if (row.subscribable.full_name != " ") {
                        html += '<span style="font-weight: bold;"><a href="/admin/subscriptions/'+row.id+'">'+row.subscribable.full_name+'</a></span><br>';
                    } else {
                        html += '<span style="font-weight: bold;"><a href="/admin/subscriptions/'+row.id+'">'+app.trans('N/A')+'</a></span><br>';
                    }

                    html += '<span class="text-muted">'+row.subscribable.email+'</span><br>';
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
                    return app.subscriptionDatatable.getStatusHtml(row);
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
                        html += 'Free billing';
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
                title: '',
                key: 'actions',
                searchable: false,
                classes: 'menu-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/subscriptions/'+row.id+'">'+app.trans('View subscription')+'</a>';
                        html += '<a class="dropdown-item" href="/admin/users/'+row.subscribable_id+'/show">'+app.trans('View user')+'</a>';
                        if (row.can_cancel) {
                            html += '<a class="dropdown-item text-danger btn-cancel-subscription" href="javascript:void(0)" data-id="'+row.id+'">'+app.trans('Cancel subscription')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'created_at',
                key: 'created_at',
                classes: 'hidden md:table-cell',
                searchable: false,
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No subscriptions were found!")
        },
        filterControl: [
            {
                key: 'type',
                title: app.trans('Type'),
                choices: [
                    { label: app.trans('Recurring'), value: 'recurring' },
                    { label: app.trans('Onetime'), value: 'onetime' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            },
            {
                key: 'gateway',
                title: app.trans('Gateway'),
                choices: [],
                shortcut: true,
                allowMultiple: true,
                showClear: true,
                value: ''
            },
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Trialing'), value: 'trialing' },
                    { label: app.trans('Active'), value: 'active' },
                    { label: app.trans('Past due'), value: 'past_due' },
                    { label: app.trans('Canceled'), value: 'canceled' },
                    { label: app.trans('Ended'), value: 'ended' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: true,
                value: ''
            }
        ],
        sortControl: {
            value: 'created_at__desc',
            options: [
                { value: 'created_at__desc', label: app.trans('Latest Created') },
                { value: 'created_at__asc', label: app.trans('Oldest Created') },
                { value: 'name__asc', label: app.trans('Name ( A - Z )') },
                { value: 'name__desc', label: app.trans('Name ( Z - A )') }
            ]
        },
        limit: 25,
        yajra: true,
        selectable: false
    }
};

app.subscriptionDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#subscriptions-datatable').length) {
        app.subscriptionDatatable.datatable = {};
        app.subscriptionDatatable.datatable = $('#subscriptions-datatable').JsDataTable(app.subscriptionDatatable.config.datatable);
        $('#subscriptions-datatable').on('table:draw.finish', function(e, api) {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }
};

app.subscriptionDatatable.init()
