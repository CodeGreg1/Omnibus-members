"use strict";

// define app.adminLanguagesDatatable object
app.adminLanguagesDatatable = {};

// handle ajax delete for language
app.adminLanguagesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Do you want to continue?"),
        message: app.trans("All users who were using this language will be reverted to the default language if it is deleted."),
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
                    url: app.adminLanguagesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.redirect(response.data.redirectTo);
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle ajax request for restoring language
app.adminLanguagesDatatable.restore = function() {

    $(document).delegate('.btn-languages-restore', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans("You're about to restore this language!"),
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
                        url: app.adminLanguagesDatatable.config.restore.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminLanguagesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.message);
                        }
                    });
                }
            }
        });
    });
};

// handle ajax request for forcing delete the languages
app.adminLanguagesDatatable.forceDelete = function() {

    $(document).delegate('.btn-languages-force-delete', 'click', function() {

        var id = $(this).attr('data-id');

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans("You're about to force delete this language!"),
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
                        url: app.adminLanguagesDatatable.config.forceDelete.route,
                        data: {id:id},
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminLanguagesDatatable.tb.refresh();
                        },
                        error: function (response) {
                            app.notify(response.message);
                        }
                    });
                }
            }
        });
    });
};

// handle configuration for language datatable
app.adminLanguagesDatatable.config = {
    delete: {
        route: '/admin/languages/multi-delete'
    },
    restore: {
        route: '/admin/languages/restore'
    },
    forceDelete: {
        route: '/admin/languages/force-delete'
    },
    datatable: {
        src: '/admin/languages/datatable',
        resourceName: { singular: app.trans("language"), plural: app.trans("languages") },
        columns: [
            {
                title: app.trans('Id'),
                key: 'id',
                classes: 'languages-id-column tb-id-column'
            },           
            {
                title: app.trans('Title'),
                key: 'title',
                classes: 'languages-title-column',
                element: function(row) {
                    var html = '<span>' + row.title;
                        html += ' <a href="/admin/languages/'+row.id+'/edit/'+row.code+'"><i class="fas fa-edit"></i> Edit language</a>';
                    html += '</span>';

                    return $(html);
                }
            },           
            {
                title: app.trans('Code'),
                key: 'code',
                classes: 'languages-code-column'
            },           
            {
                title: app.trans('Direction'),
                key: 'direction',
                classes: 'languages-direction-display-column'
            },           
            {
                title: app.trans('Active'),
                key: 'active',
                searchable: false,
                classes: 'languages-active-column',
                element: function(row) {
                    var html = '<div class="custom-control custom-checkbox">';
                        html += '<input type="checkbox" class="custom-control-input" id="col-'+row.id+'" '+(row.active==1?'checked':'')+' disabled>';
                        html += '<label class="custom-control-label" for="col-'+row.id+'"></label>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'languages-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {
                            if(app.can('admin.languages.show')) {
                                html += '<a class="dropdown-item" href="/admin/languages/'+row.id+'/show"><i class="fas fa-eye"></i> View</a>';
                            }
                            
                            if(app.can('admin.languages.edit')) {
                                html += '<a class="dropdown-item" href="/admin/languages/'+row.id+'/edit"><i class="fas fa-edit"></i> Edit</a>';
                            }
                            
                            if(app.can('admin.languages.delete')) {
                                html += '<a class="dropdown-item btn-languages-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> Delete</a>';
                            }
                        }
                            
                        if(row.deleted_at !== null) {
                            if(app.can('admin.languages.restore')) {
                                html += '<a class="dropdown-item btn-languages-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> Restore</a>';
                            }
                            
                            if(app.can('admin.languages.force-delete')) {
                                html += '<a class="dropdown-item btn-languages-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> Force delete</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No languages were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] },
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
                { value: 'title__desc', label: app.trans('Title') + ' ( Z - A )' },
                { value: 'code__asc', label: app.trans('Code') + ' ( A - Z )' },
                { value: 'code__desc', label: app.trans('Code') + ' ( Z - A )' },
                { value: 'direction__asc', label: app.trans('Direction') + ' ( A - Z )' },
                { value: 'direction__desc', label: app.trans('Direction') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminLanguagesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

// initialize functions of app.adminLanguagesDatatable object
app.adminLanguagesDatatable.init = function() {
    app.adminLanguagesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminLanguagesDatatable.tb = $("#languages-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminLanguagesDatatable.restore();
    // Force delete trashed record
    app.adminLanguagesDatatable.forceDelete();
};

// initialize app.adminLanguagesDatatable object until the document is loaded
$(document).ready(app.adminLanguagesDatatable.init());