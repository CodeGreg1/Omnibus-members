"use strict";

// define app.adminPermissionsDatatable object
app.adminPermissionsDatatable = {};

// Handle deleting permission with ajax request
app.adminPermissionsDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to delete user permission(s)!"),
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
                    url: app.adminPermissionsDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminPermissionsDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// Handle permissions datatable configuration for delete route and datatable
app.adminPermissionsDatatable.config = {
    delete: {
        route: '/admin/permissions/delete'
    },
    datatable: {
        src: '/admin/permissions/datatable',
        resourceName: { singular: "permission", plural: "permissions" },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'permission-name role-name-column'
            },
            {
                title: app.trans('Display Name'),
                key: 'display_name',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Guard Name'),
                key: 'guard_name',
                classes: 'guard-name hidden md:table-cell'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'role-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        if(app.can('admin.permissions.edit')) {
                            html += '<a class="dropdown-item" href="/admin/permissions/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [] }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No permissions were found!")
        },
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
                title: app.trans('Delete selected permissions'),
                onAction: app.adminPermissionsDatatable.delete,
                status: 'error'
            },
        ],
        limit: 25
    }
};

// initialize functions of app.adminPermissionsDatatable object
app.adminPermissionsDatatable.init = function() {
    app.adminPermissionsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminPermissionsDatatable.tb = $("#permissions-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminPermissionsDatatable object until the document is loaded
$(document).ready(app.adminPermissionsDatatable.init());