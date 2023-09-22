"use strict";

// define app.adminCategoriesDatatable object
app.adminCategoriesDatatable = {};

// handle app.adminCategoriesDatatable object on deleting category with ajax request
app.adminCategoriesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete category(s)!"),
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
                    url: app.adminCategoriesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminCategoriesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminCategoriesDatatable object on restoring category with ajax request
app.adminCategoriesDatatable.restore = function() {

    $(document).delegate('.btn-admin-categories-restore', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this category!"),
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
                        url: app.adminCategoriesDatatable.config.restore.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoriesDatatable.tb.refresh();
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

// handle app.adminCategoriesDatatable object on multi delete ajax request
app.adminCategoriesDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-categories-force-delete', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this category!"),
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
                        url: app.adminCategoriesDatatable.config.forceDelete.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminCategoriesDatatable.tb.refresh();
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

// handle app.adminCategoriesDatatable object configuration
app.adminCategoriesDatatable.config = {
    delete: {
        route: '/admin/categories/multi-delete'
    },
    restore: {
        route: '/admin/categories/restore'
    },
    forceDelete: {
        route: '/admin/categories/force-delete'
    },
    datatable: {
        src: '/admin/categories/datatable',
        resourceName: { singular: app.trans('category'), plural: app.trans('categories') },
        columns: [
                       
            {
                title: app.trans('Id'),
                key: 'id',
                searchable: true,
                classes: 'categories-id-column tb-id-column'
            },
            {
                title: app.trans('Category type name'),
                key: 'category_type.name',
                searchable: true,
                hidden: true
            },   
            {
                title: app.trans('Type'),
                key: 'category_type.type',
                searchable: true,
                classes: 'categories-category-type-column',
                element: function(row) {
                	if(row.category_type !== null) {
                    	return $('<span>'+row.category_type.type_name+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }
            },           
            {
                title: app.trans('Name'),
                key: 'name',
                searchable: true,
                classes: 'categories-name-column'
            },           
            {
                title: app.trans('Description'),
                key: 'description',
                searchable: true,
                classes: 'categories-description-column'
            },           
            {
                title: app.trans('Color'),
                key: 'color',
                searchable: true,
                classes: 'categories-color-column',
                element: function(row) {
                    var html = '<span class="badge" style="background-color: '+row.color+'; padding: unset; height: 25px; width: 25px;">&nbsp;&nbsp;&nbsp;&nbsp;<span>';

                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'categories-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {                            
                            if(app.can('admin.categories.show')) {
                                html += '<a class="dropdown-item" href="/admin/categories/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }
                                                        
                            if(app.can('admin.categories.edit')) {
                                html += '<a class="dropdown-item" href="/admin/categories/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }
                            
                            if(app.can('admin.categories.delete')) {
                                html += '<a class="dropdown-item btn-admin-categories-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }
                            
                        if(row.deleted_at !== null) {
                            if(app.can('admin.categories.restore')) {
                                html += '<a class="dropdown-item btn-admin-categories-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.categories.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-categories-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No categories were found!")
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
                { value: 'category_type__asc', label: app.trans('Category Type') + ' ( A - Z )' },
                { value: 'category_type__desc', label: app.trans('Category Type') + ' ( Z - A )' },
                { value: 'parent__asc', label: app.trans('Parent') + ' ( A - Z )' },
                { value: 'parent__desc', label: app.trans('Parent') + ' ( Z - A )' },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminCategoriesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

// initialize functions of app.adminCategoriesDatatable object
app.adminCategoriesDatatable.init = function() {
    app.adminCategoriesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminCategoriesDatatable.tb = $("#admin-categories-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminCategoriesDatatable.restore();
    // Force delete trashed record
    app.adminCategoriesDatatable.forceDelete();
};

// initialize app.adminCategoriesDatatable object until the document is loaded
$(document).ready(app.adminCategoriesDatatable.init());