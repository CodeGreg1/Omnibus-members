app.customerPaymentDatatable = {};

app.customerPaymentDatatable.config = {
    datatable: {
        src: '/admin/subscriptions/payments/datatable',
        resourceName: { singular: app.trans("payment"), plural: app.trans("payments") },
        columns: [
            {
                title: app.trans('Amount'),
                key: 'amount',
                classes: '',
                searchable: false,
            },
            {
                title: '',
                key: 'currency',
                classes: '',
                searchable: false,
                element: function(row) {
                    return $('<span>'+row.currency.code+'</span>');
                }
            },
            {
                title: '',
                key: 'status',
                classes: 'hidden md:table-cell',
                searchable: false,
                element: function(row) {
                    var label = row.status.charAt(0).toUpperCase() + row.status.slice(1);
                    var color = 'danger';
                    if (row.status === 'success') {
                        color = 'primary';
                    }
                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
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
                classes: 'menu-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/subscriptions/users/'+row.subscription.user_id+'/invoices/'+row.id+'">'+app.trans('View invoice')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'created_at',
                key: 'created_at',
                classes: 'hidden md:table-cell',
                hidden: true
            },
            {
                title: 'user',
                key: 'users.name',
                classes: 'hidden md:table-cell',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No payments were found!")
        },
        sortControl: {
            value: 'created_at__desc',
            options: [
                { value: 'created_at__desc', label: app.trans('Latest Created') },
                { value: 'created_at__asc', label: app.trans('Oldest Created') },
            ]
        },
        limit: 25,
        yajra: true,
        query: {
            customer: $('#Customer_id').val()
        },
    }
};

app.customerPaymentDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#customer-payments-datatable').length) {
        app.customerPaymentDatatable.datatable = {};
        app.customerPaymentDatatable.datatable = $('#customer-payments-datatable').JsDataTable(app.customerPaymentDatatable.config.datatable);
    }
};

app.customerPaymentDatatable.init()
