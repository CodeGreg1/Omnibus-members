app.subscriptionPaymentDatatable = {};

app.subscriptionPaymentDatatable.config = {
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
                orderable: false
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
                { value: 'created_at__asc', label: app.trans('Oldest Created') },
            ]
        },
        selectable: false,
        limit: 15,
        yajra: true,
        query: {
            subscription: $('#Subscription_id').val()
        },
    }
};

app.subscriptionPaymentDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#subscription-payments-datatable').length) {
        app.subscriptionPaymentDatatable.datatable = {};
        app.subscriptionPaymentDatatable.datatable = $('#subscription-payments-datatable').JsDataTable(app.subscriptionPaymentDatatable.config.datatable);
    }
};

app.subscriptionPaymentDatatable.init()
