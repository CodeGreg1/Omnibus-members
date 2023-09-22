app.paymentDatatable = {};

app.paymentDatatable.config = {
    datatable: {
        src: '/admin/subscriptions/payments/datatable',
        resourceName: { singular: app.trans("payment"), plural: app.trans("payments") },
        columns: [
            {
                title: app.trans('Package'),
                key: 'package',
                classes: '',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Amount'),
                key: 'amount',
                classes: '',
                searchable: false,
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell',
                searchable: false,
                element: function(row) {
                    return $('<span class="badge badge-'+row.state_color+'">'+row.state_label+'</span>');
                }
            },
            {
                title: app.trans('Subscriber'),
                key: 'subscriber',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var html = '<p style="margin-bottom: 0;">';
                    if (row.payable.subscribable.full_name != " ") {
                        html += '<span style="font-weight: bold;">'+row.payable.subscribable.full_name+'</span><br>';
                    }

                    html += '<span class="text-muted">'+row.payable.subscribable.email+'</span><br>';
                    html += '</p>';
                    return $(html);
                }
            },
            {
                title: app.trans('Gateway'),
                key: 'gateway',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var label = row.gateway.charAt(0).toUpperCase() + row.gateway.slice(1);
                    return $('<span>'+label+'</span>');
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
                classes: 'payments-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/users/'+row.payable.subscribable_id+'/show">'+app.trans('View user')+'</a>';
                        html += '<a class="dropdown-item" href="/user/invoices/'+row.transaction_id+'">'+app.trans('View invoice')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No payments were found!")
        },
        filterControl: [
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
                    { label: app.trans('Pending'), value: 'pending' },
                    { label: app.trans('Paid'), value: 'paid' },
                    { label: app.trans('Failed'), value: 'failed' }
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
                { value: 'created_at__asc', label: app.trans('Oldest Created') }
            ]
        },
        selectable: false,
        limit: 25,
        yajra: true,
    }
};

app.paymentDatatable.init = function() {
    app.paymentDatatable.datatable = {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.paymentDatatable.datatable = $('#payments-datatable').JsDataTable(app.paymentDatatable.config.datatable);
    }
};

app.paymentDatatable.init()
