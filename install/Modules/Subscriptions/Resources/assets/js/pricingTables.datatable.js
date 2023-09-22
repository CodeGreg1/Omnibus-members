"use strict";

app.pricingTableDatatable = {};

app.pricingTableDatatable.removeSelected = function(selected, table) {
    var items = selected.map(item => item.id);
    if (items) {
        app.deletePricingTable.ajax(items);
    }
};

app.pricingTableDatatable.config = {
    button: {
        removeItem: '.btn-remove-pricing-table'
    },
    datatable: {
        src: '/admin/subscriptions/pricing-tables/datatable',
        resourceName: { singular: app.trans("pricing table"), plural: app.trans("pricing tables") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: '',
                searchable: false,
                orderable: false,
                element: function(row) {
                    let html = '<a href="/admin/subscriptions/pricing-tables/'+row.id+'/show" class="text-dark">';
                    html += '<strong>'+row.name+'</strong></a>';
                    return $(html);
                }
            },
            {
                title: app.trans('Packages'),
                key: 'packages',
                classes: 'hidden md:table-cell w-50',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Active'),
                key: 'active',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    let html = '<i class="fas fa-check-circle text-lg text-success"></i>';
                    if (!row.active) {
                        html = '<i class="fas fa-circle text-lg text-secondary"></i>';
                    }
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
                        html += '<a class="dropdown-item" href="/admin/subscriptions/pricing-tables/'+row.id+'/show">'+app.trans('View pricing table')+'</a>';
                        html += '<a class="dropdown-item" href="/admin/subscriptions/pricing-tables/'+row.id+'/edit">'+app.trans('Edit pricing table')+'</a>';
                        if (row.active) {
                            html += '<a class="dropdown-item btn-disable-pricing-table" role="button" href="#" data-route="/admin/subscriptions/pricing-tables/'+row.id+'/disable">'+app.trans('Disable pricing table')+'</a>';
                        } else {
                            html += '<a class="dropdown-item btn-activate-pricing-table" role="button" href="#" data-route="/admin/subscriptions/pricing-tables/'+row.id+'/enable">'+app.trans('Activate pricing table')+'</a>';
                        }
                        html += '<a class="dropdown-item text-danger btn-remove-pricing-table" role="button" href="#" data-id="'+row.id+'">'+app.trans('Delete pricing table')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No pricing tables were found!")
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
        limit: 5,
        yajra: true,
        bulkActions: [
            {
                title: app.trans("Removed selected"),
                onAction: app.pricingTableDatatable.removeSelected.bind(app.pricingTableDatatable),
                reloadOnChange: true,
            }
        ],
    }
};

app.pricingTableDatatable.deleteItem = function() {
    $(document).delegate(this.config.button.removeItem, 'click', function() {
        var items = [$(this).data('id')];
        app.deletePricingTable.ajax(items);
    });
};

app.pricingTableDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#admin-pricing-tables').length) {
        app.pricingTableDatatable.table = {};
        app.pricingTableDatatable.table = $('#admin-pricing-tables').JsDataTable(app.pricingTableDatatable.config.datatable);

        $('#admin-pricing-tables').on('table:draw.finish', function(e, api) {
            $('[data-toggle="tooltip"]').tooltip();
        });
    }

    this.deleteItem();
};

app.pricingTableDatatable.init()
