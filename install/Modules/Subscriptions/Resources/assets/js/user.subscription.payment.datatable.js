app.userSubscriptionPaymentDatatable = {};

app.userSubscriptionPaymentDatatable.config = {
    datatable: {
        src: '/user/subscriptions/payments/datatable',
        resourceName: { singular: app.trans("payment"), plural: app.trans("payments") },
        columns: [
            {
                title: app.trans('Amount'),
                key: 'amount',
                classes: '',
                searchable: false,
            },
            {
                title: app.trans('Description'),
                key: 'description',
                classes: '',
                searchable: false
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
                title: app.trans('Date'),
                key: 'created',
                classes: 'hidden md:table-cell',
                searchable: false,
            },
            {
                title: '',
                key: 'actions',
                searchable: false,
                classes: 'subscription-payments-actions-column tb-actions-column text-right',
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
        limit: 15,
        selectable: false,
        yajra: true,
        query: {
            subscription: $('#Subscription_id').val()
        }
    }
};

app.userSubscriptionPaymentDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#user-customer-payments-datatable').length) {
        app.userSubscriptionPaymentDatatable.datatable = {};
        app.userSubscriptionPaymentDatatable.datatable = $('#user-customer-payments-datatable').JsDataTable(app.userSubscriptionPaymentDatatable.config.datatable);
    }
};

app.userSubscriptionPaymentDatatable.init()
