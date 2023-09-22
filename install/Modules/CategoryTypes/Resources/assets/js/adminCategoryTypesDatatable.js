"use strict";

// define app.adminCategoryTypesDatatable object
app.adminCategoryTypesDatatable = {};

// handle app.adminCategoryTypesDatatable object on deleting categorytype with ajax request
app.adminCategoryTypesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete category type(s)!"),
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
                    url: app.adminCategoryTypesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminCategoryTypesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminCategoryTypesDatatable object on restoring categorytype with ajax request
app.adminCategoryTypesDatatable.restore = function() {

    $(document).delegate('.btn-admin-categorytypes-restore', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this category type!"),
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

                    var actionButton = button.parents('.tb-actions-column').find('.dropdown-toggle');
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'post',
                        url: app.adminCategoryTypesDatatable.config.restore.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoryTypesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                        }
                    });
                }
            }
        });
    });
};

// handle app.adminCategoryTypesDatatable object on multi delete ajax request
app.adminCategoryTypesDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-categorytypes-force-delete', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this category type!"),
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

                    var actionButton = button.parents('.tb-actions-column').find('.dropdown-toggle');
                    var actionButtonContent = actionButton.html();

                    $.ajax({
                        type: 'delete',
                        url: app.adminCategoryTypesDatatable.config.forceDelete.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoryTypesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent(actionButton, actionButtonContent);
                        }
                    });
                }
            }
        });
    });
};

// handle app.adminCategoryTypesDatatable object configuration
app.adminCategoryTypesDatatable.config = {
    delete: {
        route: '/admin/category-types/multi-delete'
    },
    restore: {
        route: '/admin/category-types/restore'
    },
    forceDelete: {
        route: '/admin/category-types/force-delete'
    },
    datatable: {
        src: '/admin/category-types/datatable',
        resourceName: { singular: app.trans('category type'), plural: app.trans('category types') },
        columns: [
                       
            {
                title: app.trans('Id'),
                key: 'id',
                searchable: true,
                classes: 'categorytypes-id-column tb-id-column'
            },           
            {
                title: app.trans('Type'),
                key: 'type',
                searchable: true,
                classes: 'categorytypes-type-column'
            },           
            {
                title: app.trans('Name'),
                key: 'name',
                searchable: true,
                classes: 'categorytypes-name-column'
            },           
            {
                title: app.trans('Description'),
                key: 'description',
                searchable: true,
                classes: 'categorytypes-description-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'categorytypes-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {                            
                            if(app.can('admin.category-types.show')) {
                                html += '<a class="dropdown-item" href="/admin/category-types/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }
                                                        
                            if(app.can('admin.category-types.edit')) {
                                html += '<a class="dropdown-item" href="/admin/category-types/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }
                            
                            if(app.can('admin.category-types.delete')) {
                                html += '<a class="dropdown-item btn-admin-categorytypes-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }
                            
                        if(row.deleted_at !== null) {
                            if(app.can('admin.category-types.restore')) {
                                html += '<a class="dropdown-item btn-admin-categorytypes-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.category-types.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-categorytypes-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No category types were found!")
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
                { value: 'type__asc', label: app.trans('Type') + ' ( A - Z )' },
                { value: 'type__desc', label: app.trans('Type') + ' ( Z - A )' },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminCategoryTypesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

// initialize functions of app.adminCategoryTypesDatatable object
app.adminCategoryTypesDatatable.init = function() {
    app.adminCategoryTypesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminCategoryTypesDatatable.tb = $("#admin-categorytypes-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminCategoryTypesDatatable.restore();
    // Force delete trashed record
    app.adminCategoryTypesDatatable.forceDelete();
};

// initialize app.adminCategoryTypesDatatable object until the document is loaded
$(document).ready(app.adminCategoryTypesDatatable.init());