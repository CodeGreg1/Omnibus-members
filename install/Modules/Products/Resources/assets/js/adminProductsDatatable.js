app.adminProductsDatatable = {};

app.adminProductsDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete product(s)!"),
        buttons: {
            confirm: {
                label: app.trans('Yes'),
                className: 'btn-danger'
            },
            cancel: {
                label: app.trans('No'),
                className: 'btn-default'
            }
        },
        callback: function (result) {
            if ( result ) {
                $.ajax({
                    type: 'delete',
                    url: app.adminProductsDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminProductsDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

app.adminProductsDatatable.restore = function() {

    $(document).delegate('.btn-admin-products-restore', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this product!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                    $.ajax({
                        type: 'post',
                        url: app.adminProductsDatatable.config.restore.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminProductsDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
};

app.adminProductsDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-products-force-delete', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this product!"),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                    $.ajax({
                        type: 'delete',
                        url: app.adminProductsDatatable.config.forceDelete.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminProductsDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
};

app.adminProductsDatatable.config = {
    delete: {
        route: '/admin/products/multi-delete'
    },
    restore: {
        route: '/admin/products/restore'
    },
    forceDelete: {
        route: '/admin/products/force-delete'
    },
    datatable: {
        src: '/admin/products/datatable',
        resourceName: { singular: app.trans('product'), plural: app.trans('products') },
        columns: [

            {
                title: app.trans('Id'),
                key: 'id',
                searchable: true,
                classes: 'products-id-column tb-id-column'
            },
            {
                title: app.trans('Price'),
                key: 'price',
                searchable: true,
                classes: 'products-price-column'
            },
            {
                title: app.trans('Currency'),
                key: 'currency.name',
                searchable: true,
                classes: 'products-currency-id-column',
                element: function(row) {
                	if(row.currency_id !== null) {
                    	return $('<span>'+row.currency.name+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }
            },
            {
                title: app.trans('Code'),
                key: 'currency.code',
                classes: 'products-code-column',
                element: function(row) {
                	if(row.currency_id !== null) {
                		return $('<span>'+row.currency.code+'</span>');
                	}

                	return $('<span>&nbsp;</span>');
                }
            },
            {
                title: app.trans('Title'),
                key: 'title',
                searchable: true,
                classes: 'products-title-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'products-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';

                        if(row.deleted_at === null)
                        {
                            if(app.can('admin.products.show')) {
                                html += '<a class="dropdown-item" href="/admin/products/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }

                            if(app.can('admin.products.edit')) {
                                html += '<a class="dropdown-item" href="/admin/products/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }

                            if(app.can('admin.products.delete')) {
                                html += '<a class="dropdown-item btn-admin-products-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }

                        if(row.deleted_at !== null) {
                            if(app.can('admin.products.restore')) {
                                html += '<a class="dropdown-item btn-admin-products-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.products.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-products-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No products were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [{ key: 'status', value: 'All' }] },
            { label: app.trans('Trashed'), filters: [{ key: 'status', value: 'Trashed' }] }
        ],
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('All'), value: 'All' },
                    { label: app.trans('Trashed'), value: 'Trashed' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'price__asc', label: app.trans('Price') + ' ( A - Z )' },
                { value: 'price__desc', label: app.trans('Price') + ' ( Z - A )' },
                { value: 'currency_id__asc', label: app.trans('Currency') + ' ( A - Z )' },
                { value: 'currency_id__desc', label: app.trans('Currency') + ' ( Z - A )' },
                { value: 'title__asc', label: app.trans('Title') + ' ( A - Z )' },
                { value: 'title__desc', label: app.trans('Title') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminProductsDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

app.adminProductsDatatable.init = function() {
    app.adminProductsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminProductsDatatable.tb = $("#admin-products-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminProductsDatatable.restore();
    // Force delete trashed record
    app.adminProductsDatatable.forceDelete();
};

$(document).ready(app.adminProductsDatatable.init());
