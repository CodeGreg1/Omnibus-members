"use strict";

app.cartDatatable = {};

app.cartDatatable.removeSelected = function(selected, table) {
    var items = selected.map(item => item.id);

    if (items.length) {
        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("Your about to remove selected items from cart."),
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
                    var dialogRemoveCartItems = bootbox.dialog({
                        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing cart item')+'...</p>',
                        closeButton: false
                    });

                    $.ajax({
                        type: 'DELETE',
                        data: {items},
                        url: app.deleteCartItem.config.route,
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            setTimeout(function() {
                                dialogRemoveCartItems.modal('hide');
                                table.refresh();
                                app.cartList.getCartItems();
                            }, 200);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            app.notify(response.responseJSON.message);
                            setTimeout(function() {
                                dialogRemoveCartItems.modal('hide');
                            }, 200);
                        }
                    });
                }
            }
        });
    }
};

app.cartDatatable.config = {
    datatable: '#user-cart-datatable',
    options: {
        src: '/user/carts/datatable',
        resourceName: { singular: app.trans("item"), plural: app.trans("items") },
        columns: [
            {
                title: app.trans('Product'),
                key: 'name',
                classes: 'cart-prduct',
                element: function(row) {
                    var html = '<div class="d-flex">';
                    html += '<img class="mr-3 rounded" width="55" src="'+row.image+'" alt="product">';
                    html += '<div class="d-flex flex-column">';
                    html += '<a href="'+row.purchasable_path+'" class="fw-bolder">'+row.name+'</a>';
                    html += '<div class="mt-2 text-sm">';
                    html += '<div class="space-initial">';
                    html += row.description;
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Unit price'),
                key: 'price',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Quantity'),
                key: 'quantity',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var html = '<div class="mt-2 d-flex flex-column align-items-center cart-item"  data-id="'+row.id+'">';
                    html += '<div class="cart-quantity-group">';
                    html += '<button type="button" class="cart-quantity-btn cart-quantity-decrement">';
                    html += '<i class="fas fa-minus"></i></button>';
                    html += '<input type="number" class="cart-quantity-control ';
                    if (row.isStockable && (row.stock < row.quantity)) {
                        html += 'text-danger';
                    }
                    html += '" readonly value="'+row.quantity+'">';
                    html += '<button type="button" class="cart-quantity-btn cart-quantity-increment">';
                    html += '<i class="fas fa-plus"></i></button>';
                    html += '</div>';
                    if (row.isStockable) {
                        html += '<span class="text-sm text-muted">Stock: '+row.stock+'</span>';
                    }
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Total price'),
                key: 'total',
                classes: 'hidden md:table-cell fw-bold',
                element: function(row) {
                    return $('<strong>'+row.total+'</strong>');
                }
            },
            {
                title: 'Actions',
                key: 'actions',
                searchable: false,
                classes: 'menu-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<a class="cart-remove-item text-danger" data-id="'+row.id+'" href="#">'+app.trans('Delete')+'</a>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No items were found!")
        },
        sortControl: {
            value: 'created_at__desc',
            options: []
        },
        bulkActions: [
            {
                title: app.trans("Removed selected"),
                onAction: app.cartDatatable.removeSelected.bind(app.cartDatatable),
                reloadOnChange: true,
            }
        ],
        allowPagination: false,
        showSearchQuery: false,
        limit: 1000
    }
};

app.cartDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.cartDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.cartDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.cartDatatable.init());
