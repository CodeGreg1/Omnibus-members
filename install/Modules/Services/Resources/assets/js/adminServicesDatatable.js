"use strict";

// define app.adminServicesDatatable object
app.adminServicesDatatable = {};

// handle app.adminServicesDatatable object on deleting service with ajax request
app.adminServicesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete service(s)!"),
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
                    url: app.adminServicesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminServicesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminServicesDatatable object on restoring service with ajax request
app.adminServicesDatatable.restore = function() {

    $(document).delegate('.btn-admin-services-restore', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this service!"),
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
                        url: app.adminServicesDatatable.config.restore.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminServicesDatatable.tb.refresh();
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

// handle app.adminServicesDatatable object on multi delete ajax request
app.adminServicesDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-services-force-delete', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this service!"),
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
                        url: app.adminServicesDatatable.config.forceDelete.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminServicesDatatable.tb.refresh();
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

// handle app.adminServicesDatatable object configuration
app.adminServicesDatatable.config = {
    delete: {
        route: '/admin/services/multi-delete'
    },
    restore: {
        route: '/admin/services/restore'
    },
    forceDelete: {
        route: '/admin/services/force-delete'
    },
    datatable: {
        src: '/admin/services/datatable',
        resourceName: { singular: app.trans('service'), plural: app.trans('services') },
        columns: [
                       
            {
                title: app.trans('Icon'),
                key: 'icon',
                searchable: true,
                classes: 'services-icon-column',
                element: function(row) {
                    var html = '<span class="'+row.icon+'"></span>';

                    return $(html);
                }
            },           
            {
                title: app.trans('Title'),
                key: 'title',
                searchable: true,
                classes: 'services-title-column'
            },           
            {
                title: app.trans('Content'),
                key: 'content',
                searchable: true,
                classes: 'services-content-column',
                element: function(row) {
                    var html = $("<textarea/>").html(row.content).text();

                    return $(html);
                }
            },
            {
                title: app.trans('Visibility'),
                key: 'visibility',
                searchable: false,
                classes: 'services-visibility-column',
                element: function(row) {
                    var html = '<span class="badge badge-'+row.visibility_details.color+'">' + row.visibility_details.name + '</span>';
                    
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'services-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {                            
                            if(app.can('admin.services.show')) {
                                html += '<a class="dropdown-item" href="/admin/services/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }
                                                        
                            if(app.can('admin.services.edit')) {
                                html += '<a class="dropdown-item" href="/admin/services/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }
                            
                            if(app.can('admin.services.delete')) {
                                html += '<a class="dropdown-item btn-admin-services-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }
                            
                        if(row.deleted_at !== null) {
                            if(app.can('admin.services.restore')) {
                                html += '<a class="dropdown-item btn-admin-services-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.services.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-services-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No service's found!")
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
                { value: 'title__asc', label: app.trans('Title') + ' ( A - Z )' },
                { value: 'title__desc', label: app.trans('Title') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminServicesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 50
    }
};

// initialize functions of app.adminServicesDatatable object
app.adminServicesDatatable.init = function() {
    app.adminServicesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminServicesDatatable.tb = $("#admin-services-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminServicesDatatable.restore();
    // Force delete trashed record
    app.adminServicesDatatable.forceDelete();
};

// initialize app.adminServicesDatatable object until the document is loaded
$(document).ready(app.adminServicesDatatable.init());