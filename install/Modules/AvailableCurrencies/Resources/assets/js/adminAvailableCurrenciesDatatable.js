"use strict";

// define adminAvailableCurrenciesDatatable object
app.adminAvailableCurrenciesDatatable = {};

// handle adminAvailableCurrenciesDatatable object & datatable configuration
app.adminAvailableCurrenciesDatatable.config = {
    delete: {
        route: '/admin/currencies/multi-delete'
    },
    datatable: {
        selectable: false,
        src: '/admin/currencies/datatable',
        resourceName: { singular: app.trans('currency'), plural: app.trans('currencies') },
        columns: [         
            {
                title: app.trans('Name'),
                key: 'name',
                searchable: true,
                classes: 'availablecurrencies-name-column'
            }, 
            {
                title: app.trans('Exchange Rate'),
                key: 'exchange_rate',
                searchable: true,
                classes: 'availablecurrencies-exchange-rate-column'
            },          
            {
                title: app.trans('Symbol'),
                key: 'symbol',
                searchable: true,
                classes: 'availablecurrencies-symbol-column'
            },           
            {
                title: app.trans('Code'),
                key: 'code',
                searchable: true,
                classes: 'availablecurrencies-code-column'
            },
            {
                title: app.trans('Format'),
                key: 'format',
                searchable: true,
                classes: 'availablecurrencies-format-column'
            },
            {
                title: app.trans('System currency'),
                key: 'is_default',
                searchable: false,
                classes: 'availablecurrencies-is_default-column',
                element: function(row) {
                    if(row.is_default) {
                        return $('<span class="badge badge-success">' + app.trans('Yes') + '</span>');
                    }

                    return $('<span class="badge badge-danger">' + app.trans('No') + '</span>');
                }
            },  
            {
                title: app.trans('Status'),
                key: 'status',
                searchable: false,
                classes: 'availablecurrencies-status-column',
                element: function(row) {
                    if(row.status) {
                        return $('<span class="badge badge-success">' + app.trans('Enabled') + '</span>');
                    }

                    return $('<span class="badge badge-danger">' + app.trans('Disabled') + '</span>');
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'availablecurrencies-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                                                        
                            if(app.can('admin.available-currencies.edit')) {
                                html += '<a class="dropdown-item" href="/admin/currencies/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }
                            
                            

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No available currencies were found!")
        },
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'currency__asc', label: app.trans('Currency') + ' ( A - Z )' },
                { value: 'currency__desc', label: app.trans('Currency') + ' ( Z - A )' },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' },
                { value: 'symbol__asc', label: app.trans('Symbol') + ' ( A - Z )' },
                { value: 'symbol__desc', label: app.trans('Symbol') + ' ( Z - A )' },
                { value: 'code__asc', label: app.trans('Code') + ' ( A - Z )' },
                { value: 'code__desc', label: app.trans('Code') + ' ( Z - A )' },
                { value: 'exchange_rate__asc', label: app.trans('Exchange Rate') + ' ( A - Z )' },
                { value: 'exchange_rate__desc', label: app.trans('Exchange Rate') + ' ( Z - A )' }
            ]
        },
        filterControl: [
            {
                key: 'system_currency',
                title: app.trans('System currency'),
                choices: [
                    { label: app.trans('Yes'), value: 1 },
                    { label: app.trans('No'), value: 0 }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            },
            {
                key: 'currency_status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Enabled'), value: 1 },
                    { label: app.trans('Disabled'), value: 0 }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],
        bulkActions: [
            
        ],
        limit: 10
    }
};

// handle initialization of datatable
app.adminAvailableCurrenciesDatatable.init = function() {
    app.adminAvailableCurrenciesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminAvailableCurrenciesDatatable.tb = $("#admin-availablecurrencies-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize adminAvailableCurrenciesDatatable object until the document is loaded
$(document).ready(app.adminAvailableCurrenciesDatatable.init());