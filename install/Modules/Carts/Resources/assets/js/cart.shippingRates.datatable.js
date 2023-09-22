"use strict";

app.shippingRateDatatable = {};

app.shippingRateDatatable.removeSelected = function(selected, table) {
    var rates = selected.map(item => item.id);

    if (rates.length) {
        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("Your about to remove selected selected shipping rates."),
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
                    var dialogRemoveShippingRates = bootbox.dialog({
                        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing cart item')+'...</p>',
                        closeButton: false
                    });

                    $.ajax({
                        type: 'DELETE',
                        data: {rates},
                        url: app.shippingRateDatatable.config.route,
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            setTimeout(function() {
                                dialogRemoveShippingRates.modal('hide');
                                table.refresh();
                            }, 200);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            app.notify(response.responseJSON.message);
                            setTimeout(function() {
                                dialogRemoveShippingRates.modal('hide');
                            }, 200);
                        }
                    });
                }
            }
        });
    }
};

app.shippingRateDatatable.config = {
    datatable: '#admin-shipping-rates-datatable',
    route: '/admin/shipping-rates/delete',
    options: {
        src: '/admin/shipping-rates/datatable',
        resourceName: { singular: app.trans("shipping rate"), plural: app.trans("shipping rates") },
        columns: [
            {
                title: app.trans('Title'),
                key: 'title',
                classes: 'shipping-rate-title'
            },
            {
                title: app.trans('Price'),
                key: 'price_display',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Status'),
                key: 'active',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var color = 'secondary';
                    var label = 'Archived';
                    if (row.active) {
                        color = 'primary';
                        label = 'Active';
                    }
                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: 'Actions',
                key: 'actions',
                searchable: false,
                classes: 'shipping-rate-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item btn-edit-shipping-rate" href="#" data-id="'+row.id+'">'+app.trans('Edit shipping rate')+'</a>';
                        html += '<a class="dropdown-item text-danger btn-delete-shipping-rate" data-route="/admin/shipping-rates/delete" data-id="'+row.id+'" href="#">'+app.trans('Delete shipping rate')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'price',
                key: 'price',
                hidden: true
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No shipping rates were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Active'), value: 'active' },
                    { label: app.trans('Archived'), value: 'archived' }
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
                { value: 'created_at__desc', label: 'Latest Created' },
                { value: 'created_at__asc', label: 'Oldest Created' },
                { value: 'title__asc', label: 'Title ( A - Z )' },
                { value: 'title__desc', label: 'Title ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans("Removed selected"),
                onAction: app.shippingRateDatatable.removeSelected.bind(app.shippingRateDatatable),
                reloadOnChange: true,
            }
        ],
        limit: 25
    }
};

app.shippingRateDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.shippingRateDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.shippingRateDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.shippingRateDatatable.init());
