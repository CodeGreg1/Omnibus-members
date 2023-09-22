"use strict";

// define app.adminMenusDatatable object
app.adminMenusDatatable = {};

// ajax request for deleting menus
app.adminMenusDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to delete menu!"),
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
                    url: app.adminMenusDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminMenusDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle menus datatable and delete route configuration
app.adminMenusDatatable.config = {
    delete: {
        route: '/admin/menus/delete'
    },
    datatable: {
        src: '/admin/menus/datatable',
        resourceName: { singular: "menu", plural: "menus" },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'menu-name-column'
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'menu-type-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'menu-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        
                        if(app.can('admin.menus.edit')) {
                            html += '<a class="dropdown-item" href="/admin/menus/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                        }
                    
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No menus were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected menus'),
                onAction: app.adminMenusDatatable.delete,
                status: 'error'
            },
        ],
        limit: 25
    }
};

// initialize functions of app.adminMenusDatatable object
app.adminMenusDatatable.init = function() {
    app.adminMenusDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminMenusDatatable.tb = $("#menus-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminMenusDatatable object until the document is loaded
$(document).ready(app.adminMenusDatatable.init());