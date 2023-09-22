"use strict";

app.adminDatabaseBackupDatatable = {};

app.adminDatabaseBackupDatatable.delete = function(selected, table) {
    const files = selected.map(item => item.file);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to delete file(s)!"),
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
                    url: '/admin/database-backup/delete',
                    data: {files},
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

app.adminDatabaseBackupDatatable.config = {
    datatable: '#admin-database-backup-datatable',
    options: {
        src: '/admin/database-backup/datatable',
        resourceName: { singular: app.trans("database backup"), plural: app.trans("database backups") },
        columns: [
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'database-backup-date-row'
            },
            {
                title: app.trans('Actions'),
                key: 'date',
                classes: 'database-backup-actions-row',
                element: function(row) {
                    return $(`<div class="d-flex"><a href="javascript:void(0)" class="btn btn-success btn-sm btn-download-database-backup-row mr-1" data-id="${row.file}"><i class="fas fa-download"></i></a><a href="javascript:void(0)" class="btn btn-danger btn-sm btn-delete-database-backup-row" data-id="${row.file}"><i class="fas fa-trash"></i></a></div>`);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No database backups were found!")
        },
        selectable: false,
        showSearchQuery: false,
        limit: 25
    }
};

app.adminDatabaseBackupDatatable.handleDownload = function() {
    const self = this;
    $(document).delegate('.btn-download-database-backup-row', 'click', function() {
        location.href = '/admin/database-backup/download?file=' + $(this).data('id');
    });
};

app.adminDatabaseBackupDatatable.handleDelete = function() {
    const self = this;
    $(document).delegate('.btn-delete-database-backup-row', 'click', function() {
        self.delete([{file: $(this).data('id')}], app.adminDatabaseBackupDatatable.tb);
    });
};

app.adminDatabaseBackupDatatable.init = function() {
    app.adminDatabaseBackupDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminDatabaseBackupDatatable.tb = $(this.config.datatable).JsDataTable(this.config.options);
    }

    this.handleDownload();
    this.handleDelete();
};

$(document).ready(app.adminDatabaseBackupDatatable.init());
