app.customerDatatable = {};

app.customerDatatable.config = {
    datatable: {
        src: '/admin/subscriptions/customers/datatable',
        resourceName: { singular: app.trans("customer"), plural: app.trans("customers") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: '',
                element: function(row) {
                    var name = row.name;
                    if (!name && row.users.length) {
                        name = row.users[0].name;
                        if (!name) {
                            name = row.users[0].email;
                        }
                    }
                    return $('<a href="/admin/subscriptions/customers/'+row.id+'" class="fw-bolder">'+name+'</a>');
                }
            },
            {
                title: app.trans('Email'),
                key: 'email',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Gateway'),
                key: 'gateway',
                classes: 'hidden md:table-cell',
                element: function(row) {
                    var gateway = row.gateway.charAt(0).toUpperCase() + row.gateway.slice(1);
                    return $('<span>'+gateway+'</span>');
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'hidden md:table-cell text-right',
                searchable: false,
            },
            {
                title: 'created_at',
                key: 'created_at',
                classes: 'hidden md:table-cell',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No customers were found!")
        },
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
    }
};

app.customerDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#customers-datatable').length) {
        app.customerDatatable.datatable = {};
        app.customerDatatable.datatable = $('#customers-datatable').JsDataTable(app.customerDatatable.config.datatable);
    }
};

$(document).ready(app.customerDatatable.init());
