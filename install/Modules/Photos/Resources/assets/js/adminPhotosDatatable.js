"use strict";

app.adminPhotosDatatable = {};

app.adminPhotosDatatable.deleteImage = function(selected, table) {
    const images = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to delete photo(s)!"),
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
                var dialogAjaxRequest = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Processing')+'...</p>',
                    closeButton: false
                });

                $.ajax({
                    type: 'DELETE',
                    url: '/admin/photos/delete-images',
                    data: {images},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        table.refresh();
                        setTimeout(function() {
                            dialogAjaxRequest.modal('hide');
                        }, 350);
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                        setTimeout(function() {
                            dialogAjaxRequest.modal('hide');
                        }, 350);
                    }
                });
            }
        }
    });
};

app.adminPhotosDatatable.downloadImage = function(selected, table) {
    // var dialogAjaxRequest = bootbox.dialog({
    //     message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Downloading')+'...</p>',
    //     closeButton: false
    // });
    location.href = '/admin/photos/download?images=' + selected.map(function(item) {return item.id}).join(",");;
};

app.adminPhotosDatatable.config = {
    datatable: '#admin-photos-datatable',
    options: {
        src: '/admin/photos/datatable',
        resourceName: { singular: app.trans("photo"), plural: app.trans("photos") },
        columns: [
            {
                title: app.trans('Image'),
                key: 'preview',
                classes: 'admin-photo-preview-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <div class="d-flex flex-column">
                            <img src="${row.preview_url}" style="width: 60px; height: 60px;">
                        </div>
                    `);
                }
            },
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'admin-photo-name-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Folder'),
                key: 'folder',
                classes: 'admin-photo-folder-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`
                        <span class="d-flex flex-column">
                            ${row.model.name}
                        </span>
                    `);
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'admin-photo-created-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Size'),
                key: 'file_size',
                classes: 'admin-photo-size-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'admin-affiliate-actions-row tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="admin-affiliate-action-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu" data-button="#admin-affiliate-action-dropdown">';
                        html += '<a class="dropdown-item btn-admin-photo-image-delete" role="button" href="javascript:void(0)" data-id="'+row.id+'">'+app.trans('Delete image')+'</a>';
                        html += '<a class="dropdown-item btn-admin-photo-image-download" role="button" href="javascript:void(0)" data-id="'+row.id+'">'+app.trans('Download image')+'</a>';
                        html += '<a class="dropdown-item" href="'+row.original_url+'" target="_blank">'+app.trans('View image')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No photos were found!")
        },
        filterControl: [
            {
                key: 'folder',
                title: app.trans('Folder'),
                choices: [],
                shortcut: true,
                allowMultiple: true,
                showClear: true,
                value: ''
            }
        ],
        sortControl: {
            value: 'created_at__desc',
            options: [
                { value: 'created_at__desc', label: app.trans('Latest Created') },
                { value: 'created_at__asc', label: app.trans('Oldest Created') }
            ]
        },
        bulkActions: [
            {
                title: app.trans("Delete selected"),
                onAction: app.adminPhotosDatatable.deleteImage.bind(app.adminPhotosDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Download selected"),
                onAction: app.adminPhotosDatatable.downloadImage.bind(app.adminPhotosDatatable),
                reloadOnChange: true,
            }
        ],
        limit: 25,
        load: false
    }
};

app.adminPhotosDatatable.handleDeleteImage = function() {
    const self = this;
    $(document).delegate('.btn-admin-photo-image-delete', 'click', function() {
        self.deleteImage([{id: $(this).data('id')}], app.adminPhotosDatatable.table);
    });
};

app.adminPhotosDatatable.handleDownloadImage = function() {
    const self = this;
    $(document).delegate('.btn-admin-photo-image-download', 'click', function() {
        self.downloadImage([{id: $(this).data('id')}], app.adminPhotosDatatable.table);
    });
};

app.adminPhotosDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminPhotosDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminPhotosDatatable.init = function() {
    this.initDatatable();
    this.handleDeleteImage();
    this.handleDownloadImage();
};

$(document).ready(app.adminPhotosDatatable.init());
