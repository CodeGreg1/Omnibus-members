"use strict";

// define app.modulesDatatable object
app.modulesDatatable = {};

// handle on deleting module ajax request
app.modulesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete module(s)!"),
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
                    url: app.modulesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.modulesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle on restoring module with ajax request
app.modulesDatatable.restore = function() {

    $(document).delegate('.btn-modules-restore', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans("You're about to restore this module!"),
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
                        type: 'post',
                        url: app.modulesDatatable.config.restore.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.modulesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
};

// handle on force deleting module with ajax request
app.modulesDatatable.forceDelete = function() {

    $(document).delegate('.btn-modules-force-delete', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans("You're about to force delete this module!"),
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
                        url: app.modulesDatatable.config.forceDelete.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.modulesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                        }
                    });
                }
            }
        });
    });
};

// handle on modulesDatable object configuration included with datatable
app.modulesDatatable.config = {
    delete: {
        route: '/admin/module/multi-delete'
    },
    restore: {
        route: '/admin/module/restore'
    },
    forceDelete: {
        route: '/admin/module/force-delete'
    },
    datatable: {
        selectable: false,
        src: '/admin/module/datatable',
        resourceName: { singular: "module", plural: "modules" },
        columns: [
                       
            {
                title: app.trans('Id'),
                key: 'id',
                classes: 'modules-id-column tb-id-column'
            },           
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'modules-name-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'modules-actions-column tb-actions-column',
                element: function(row) {

                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                        
                        if(row.is_core == 0) {

                            var crudType = '';

                            if(row.attributes['included']["admin"] !== undefined && row.attributes['included']["admin"] == 'on') {
                                crudType = 'admin/';
                            } else if(row.attributes['included']["user"] !== undefined && row.attributes['included']["user"] == 'on') {
                                crudType = 'user/';
                            }

                            html += '<a class="dropdown-item" href="/' + crudType + row.handle +'"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            
                            if(app.can('admin.modules.edit')) {
                                html += '<a class="dropdown-item" href="/admin/module/'+row.id+'/edit"><i class="fas fa-retweet"></i> '+app.trans('Rebuild')+'</a>';
                            }

                            if(app.can('admin.modules.show-language')) {
                                html += '<a class="dropdown-item" href="/admin/module/'+row.id+'/show/languages"><i class="fas fa-language"></i> '+app.trans('Edit Language')+'</a>';
                            }
                            
                            if(app.can('admin.modules.delete')) {
                                html += '<a class="dropdown-item btn-modules-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> '+app.trans('Remove')+'</a>';
                            }
                        } else {
                            if(app.can('admin.modules.show-language')) {
                                html += '<a class="dropdown-item" href="/admin/module/'+row.id+'/show/languages"><i class="fas fa-language"></i> '+app.trans('Edit Language')+'</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No modules were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] },
            { label: app.trans('Core'), filters: [{ key: 'status', value: 'Core' }] }
        ],
        filterControl: [
                {
                    key: 'status',
                    title: app.trans('Status'),
                    choices: [
                        { label: app.trans('All'), value: 'All' },
                        { label: app.trans('Core'), value: 'Core' }
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
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.modulesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 25
    }
};

// handle on initializing datatable(), restore(), and forceDelete() functions
app.modulesDatatable.init = function() {
    app.modulesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.modulesDatatable.tb = $("#modules-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.modulesDatatable.restore();
    // Force delete trashed record
    app.modulesDatatable.forceDelete();
};

// initialize app.modulesDatatable object until the document is loaded
$(document).ready(app.modulesDatatable.init());