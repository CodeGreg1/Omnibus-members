"use strict";

app.adminOrderDatatable = {};

app.adminOrderDatatable.config = {
    datatable: '#admin-orders-datatable',
    options: {
        src: '/admin/orders/datatable',
        resourceName: { singular: app.trans("order"), plural: app.trans("orders") },
        columns: [
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'order-date-column',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Amount'),
                key: 'amount',
                classes: 'order-amount-column hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Items'),
                key: 'items_count',
                classes: 'order-amount-column hidden md:table-cell',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Tracking'),
                key: 'status',
                classes: 'order-status-column hidden md:table-cell',
                element: function(row) {
                    var label = row.status.charAt(0).toUpperCase() + row.status.slice(1);
                    var html = '<div class="btn-group">';
                    html += '<button type="button" class="btn btn-sm btn-'+row.tracking_status_color+'">'+label+'</button>';
                    if (row.tracking_transitions.length) {
                        html += '<button type="button" class="btn btn-sm btn-'+row.tracking_status_color+' dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false"><span class="sr-only">Toggle Dropdown</span></button>';
                        html += '<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(119px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">';
                        for (let index = 0; index < row.tracking_transitions.length; index++) {
                            var state = row.tracking_transitions[index];
                            var words = state.split('_');
                            var label = words.map((word) => {
                                return word[0].toUpperCase() + word.substring(1);
                            }).join(' ');
                            html += '<a class="dropdown-item btn-tracking-transition" data-id="'+row.id+'" data-status="'+state+'" href="#" role="button">'+label+'</a>';
                        }
                        html += '</div>';
                    }
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Payment status'),
                key: 'paid',
                classes: 'order-paid-column hidden md:table-cell',
                element: function(row) {
                    var label = row.status.charAt(0).toUpperCase() + row.status.slice(1);
                    var color = 'warning';
                    var label = 'Pending';
                    if (row.paid) {
                        color = 'primary';
                        label = 'Paid';
                    }
                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
                    return $(html);
                }
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
                        html += '<a class="dropdown-item" href="/admin/orders/'+row.id+'/show">'+app.trans('Show details')+'</a>';
                        html += '<a class="dropdown-item btn-download-invoice-order" href="/admin/orders/'+row.id+'/download-invoice">'+app.trans('Download invoice')+'</a>';
                        if (!row.paid) {
                            html += '<a class="dropdown-item text-danger btn-cancel-order" data-route="/admin/orders/'+row.id+'/cancel" href="#">'+app.trans('Cancel order')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') }
            ]
        },
        selectable: false
    }
};

app.adminOrderDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminOrderDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminOrderDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.adminOrderDatatable.init());
