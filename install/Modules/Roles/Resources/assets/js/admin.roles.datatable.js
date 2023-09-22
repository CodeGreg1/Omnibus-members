"use strict";

// define app.adminRolesDatatable object
app.adminRolesDatatable = {};

// handle app.adminRolesDatatable object deleting role ajax request
app.adminRolesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to delete user role(s)!"),
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
                    url: app.adminRolesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminRolesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminRolesDatatable object configuration
app.adminRolesDatatable.config = {
    delete: {
        route: '/admin/roles/delete'
    },
    datatable: {
        src: '/admin/roles/datatable',
        resourceName: { singular: app.trans("role"), plural: app.trans("roles") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'role-name-column'
            },
            {
                title: app.trans('Display Name'),
                key: 'display_name',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'hidden md:table-cell'
            },
            {
                title: app.trans('Description'),
                key: 'description',
                classes: 'hidden md:table-cell'
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
                        if(app.can('admin.roles.edit')) {
                            html += '<a class="dropdown-item" href="/admin/roles/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No roles were found!")
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
                title: app.trans('Delete selected roles'),
                onAction: app.adminRolesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 25
    }
};

// initialize functions of app.adminRolesDatatable object
app.adminRolesDatatable.init = function() {
    app.adminRolesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminRolesDatatable.tb = $("#roles-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminRolesDatatable object until the document is loaded
$(document).ready(app.adminRolesDatatable.init());