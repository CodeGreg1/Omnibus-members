app.userProductsDatatable = {};

app.userProductsDatatable.delete = function(selected) {

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
                    url: app.userProductsDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.userProductsDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

app.userProductsDatatable.restore = function() {

    $(document).delegate('.btn-user-products-restore', 'click', function() {

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
                        url: app.userProductsDatatable.config.restore.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.userProductsDatatable.tb.refresh();
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

app.userProductsDatatable.forceDelete = function() {

    $(document).delegate('.btn-user-products-force-delete', 'click', function() {

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
                        url: app.userProductsDatatable.config.forceDelete.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.userProductsDatatable.tb.refresh();
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

app.userProductsDatatable.config = {
    delete: {
        route: '/user/products/multi-delete'
    },
    restore: {
        route: '/user/products/restore'
    },
    forceDelete: {
        route: '/user/products/force-delete'
    },
    datatable: {
        src: '/user/products/datatable',
        resourceName: { singular: app.trans('product'), plural: app.trans('products') },
        columns: [
            {
                title: app.trans('Product'),
                key: 'price',
                searchable: true,
                classes: 'products-title-column',
                element: function(row) {
                    var html = '<div class="d-flex">';
                    html += '<img class="mr-3 rounded" width="55" src="'+row.image+'" alt="product">';
                    html += '<div class="d-flex flex-column">';
                    html += '<a href="/user/products/'+row.id+'/show" class="fw-bolder">'+row.title+'</a>';
                    html += '<div class="mt-2 text-sm">';
                    html += '<p>'+row.description+'</p>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Price'),
                key: 'unit_price',
                classes: 'products-price-column',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Stock'),
                key: 'stock',
                searchable: true,
                classes: 'products-stock-column'
            },
            {
                title: app.trans('Rating'),
                key: 'rating',
                searchable: true,
                classes: 'products-rating-column'
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
                            if(app.can('user.products.show')) {
                                html += '<a class="dropdown-item" href="/user/products/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }

                            if(app.can('user.products.edit')) {
                                html += '<a class="dropdown-item" href="/user/products/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }

                            if(app.can('user.products.delete')) {
                                html += '<a class="dropdown-item btn-user-products-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }

                        if(row.deleted_at !== null) {
                            if(app.can('user.products.restore')) {
                                html += '<a class="dropdown-item btn-user-products-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('user.products.force-delete')) {
                                html += '<a class="dropdown-item btn-user-products-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '<a class="dropdown-item btn-add-single-cart-item" href="javascript:void(0)" data-object="'+row.purchasable_object+'" data-key="'+row.purchasable_key+'"><i class="fas fa-shopping-cart"></i> ' + app.trans('Add to cart') + '</a>';

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No products were found!")
        },
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'price__asc', label: app.trans('Price') + ' ( A - Z )' },
                { value: 'price__desc', label: app.trans('Price') + ' ( Z - A )' },
                { value: 'title__asc', label: app.trans('Title') + ' ( A - Z )' },
                { value: 'title__desc', label: app.trans('Title') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.userProductsDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

app.userProductsDatatable.init = function() {
    app.userProductsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.userProductsDatatable.tb = $("#user-products-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.userProductsDatatable.restore();
    // Force delete trashed record
    app.userProductsDatatable.forceDelete();
};

$(document).ready(app.userProductsDatatable.init());
