"use strict";

// define app.adminManualGatewayDatatable object
app.adminManualGatewayDatatable = {};

// handle app.adminManualGatewayDatatable object on deleting wearable with ajax request
app.adminManualGatewayDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete manual gateway(s)!"),
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
                    url: app.adminManualGatewayDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminManualGatewayDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminManualGatewayDatatable object on restoring wearable with ajax request
app.adminManualGatewayDatatable.restore = function() {

    $(document).delegate('.btn-admin-manual-gateway-restore', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this manual gateway!"),
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
                        url: app.adminManualGatewayDatatable.config.restore.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminManualGatewayDatatable.tb.refresh();
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

// handle app.adminManualGatewayDatatable object on multi delete ajax request
app.adminManualGatewayDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-manual-gateway-force-delete', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this manual gateway!"),
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
                        url: app.adminManualGatewayDatatable.config.forceDelete.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminManualGatewayDatatable.tb.refresh();
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

// handle app.adminManualGatewayDatatable object configuration
app.adminManualGatewayDatatable.config = {
    delete: {
        route: '/admin/manual-gateways/multi-delete'
    },
    restore: {
        route: '/admin/manual-gateways/restore'
    },
    forceDelete: {
        route: '/admin/manual-gateways/force-delete'
    },
    datatable: {
        src: '/admin/manual-gateways/datatable',
        resourceName: { singular: app.trans('manual gateway'), plural: app.trans('manual gateways') },
        columns: [
            {
                title: app.trans('Id'),
                key: 'id',
                searchable: true,
                classes: 'manual-gateways-id-column tb-id-column'
            },
            {
                title: app.trans('Image'),
                key: 'image',
                searchable: false,
                classes: 'manual-gateways-image-column',
                element: function(row) {
                    var html = '<span>&nbsp;</span>';

                    if (row.image.length) {

                        $.each(row.image, function(key, entry) {
                            var src = entry.thumbnail;

                            if(entry.generated_conversions.length == 0 && app.baseImageType.lists.indexOf(entry.mime_type) !== -1) {
                                src = entry.url;
                            }

                            html += '<a href="'+entry.url+'" target="_blank" title="'+entry.file_name+'">';
                            html += '<img src="'+src+'" class="img-thumbnail thumbnail-size">';
                            html += '</a>';
                        });

                    }

                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'manual-gateways-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';

                        if(row.deleted_at === null)
                        {
                            if(app.can('admin.wearables.show')) {
                                html += '<a class="dropdown-item" href="/admin/manual-gateways/'+row.id+'/show"><i class="fas fa-eye"></i> '+app.trans('View')+'</a>';
                            }

                            if(app.can('admin.wearables.edit')) {
                                html += '<a class="dropdown-item" href="/admin/manual-gateways/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }

                            if(app.can('admin.wearables.delete')) {
                                html += '<a class="dropdown-item btn-admin-wearables-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                        }

                        if(row.deleted_at !== null) {
                            if(app.can('admin.wearables.restore')) {
                                html += '<a class="dropdown-item btn-admin-wearables-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.wearables.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-wearables-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No manual gateways were found!")
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
                { value: 'image__asc', label: app.trans('Image') + ' ( A - Z )' },
                { value: 'image__desc', label: app.trans('Image') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminManualGatewayDatatable.delete,
                status: 'error'
            },
        ],
        limit: 10
    }
};

// initialize functions of app.adminManualGatewayDatatable object
app.adminManualGatewayDatatable.init = function() {
    app.adminManualGatewayDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminManualGatewayDatatable.tb = $("#admin-manual-gateways-datatable").JsDataTable(this.config.datatable);
    }

    // Restore trashed record
    app.adminManualGatewayDatatable.restore();
    // Force delete trashed record
    app.adminManualGatewayDatatable.forceDelete();
};

// initialize app.adminManualGatewayDatatable object until the document is loaded
$(document).ready(app.adminManualGatewayDatatable.init());
