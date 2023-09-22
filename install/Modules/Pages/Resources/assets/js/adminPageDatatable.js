"use strict";

app.adminPageDatatable = {};

app.adminPageDatatable.removeSelected = function(selected, table) {
    const pages = selected.filter(item => !item.default).map(item => item.id);

    if (!pages.length) {
        app.notify(app.trans('You cannot remove default pages.'));
        return;
    }

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to delete page(s)!. Default pages that are selected will not be removed."),
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
                app.adminPageDatatable.tableAjax(
                    'delete',
                    {pages},
                    '/admin/pages/destroy',
                    table
                );
            }
        }
    });
};

app.adminPageDatatable.moveToDraft = function(selected, table) {
    const pages = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to move selected page(s) to draft!"),
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
                app.adminPageDatatable.tableAjax(
                    'post',
                    {pages},
                    '/admin/pages/move-to-draft',
                    table
                );
            }
        }
    });
};

app.adminPageDatatable.moveToPublished = function(selected, table) {
    const pages = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to move selected page(s) to published!"),
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
                app.adminPageDatatable.tableAjax(
                    'post',
                    {pages},
                    '/admin/pages/move-to-published',
                    table
                );
            }
        }
    });
};

app.adminPageDatatable.tableAjax = function(method, data, route, table) {
    var dialogAjaxRequest = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Processing')+'...</p>',
        closeButton: false
    });

    $.ajax({
        type: method,
        url: route,
        data: data,
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
},

app.adminPageDatatable.config = {
    datatable: '#admin-pages-datatable',
    route: '/admin/pages/datatable',
    options: {
        src: '/admin/pages/datatable',
        resourceName: { singular: app.trans("page"), plural: app.trans("pages") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'page-name-row'
            },
            {
                title: app.trans('Default'),
                key: 'default',
                classes: 'page-default-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    if (row.default) {
                        return $('<i class="fas fa-check text-success"></i>');
                    }
                    return $('<i class="fas fa-circle text-muted"></i>');
                }
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'page-type-row',
                element: function(row) {
                    return $('<span>'+row.type_label+'</span>');
                }
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'page-status-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var label = row.status.charAt(0).toUpperCase() + row.status.slice(1);
                    var html = '<span class="badge badge-'+row.status_color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'page-created-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'page-actions-column tb-actions-column text-right',
                element: function(row) {
                    var statuses = ['draft', 'published'];
                    var index = statuses.indexOf(row.status);
                    if (index !== -1) {
                        statuses.splice(index, 1);
                    }
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        if (row.status === 'published') {
                            var url = '/pages/'+row.slug;
                            if (row.slug === 'home') {
                                url = '/';
                            }
                            html += '<a class="dropdown-item" href="'+url+'" target="_blank">'+app.trans('View page in site')+'</a>';
                        } else {
                            html += '<a class="dropdown-item" href="/admin/pages/'+row.id+'/preview" target="_blank">'+app.trans('View preview')+'</a>';
                        }
                        html += '<a class="dropdown-item" href="/admin/pages/'+row.id+'/edit">'+app.trans('Edit page')+'</a>';
                        for (let index = 0; index < statuses.length; index++) {
                            const status = statuses[index];
                            html += '<a class="dropdown-item btn-move-to-'+status+'-page page-status-transition-btn" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Move to :status', {status})+'</a>';
                        }

                        html += '<a class="dropdown-item btn-duplicate-page" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Duplicate page')+'</a>';

                        if (!row.default) {
                            html += '<a class="dropdown-item text-danger btn-delete-page" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Delete page')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No pages were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Published'), value: 'Published' },
                    { label: app.trans('Draft'), value: 'Draft' },
                ],
                hidden: true,
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            },
            {
                key: 'type',
                title: app.trans('Type'),
                choices: [],
                shortcut: true,
                allowMultiple: false,
                value: ''
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [] },
            { label: app.trans('Published'), filters: [{ key: 'status', value: 'Published' }] },
            { label: app.trans('Draft'), filters: [{ key: 'status', value: 'Draft' }] }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest Created') },
                { value: 'id__asc', label: app.trans('Oldest Created') }
            ]
        },
        bulkActions: [
            {
                title: app.trans("Removed selected"),
                onAction: app.adminPageDatatable.removeSelected.bind(app.adminPageDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Move to Draft"),
                onAction: app.adminPageDatatable.moveToDraft.bind(app.adminPageDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Move to Published"),
                onAction: app.adminPageDatatable.moveToPublished.bind(app.adminPageDatatable),
                reloadOnChange: true,
            }
        ],
        limit: 25
    }
};

app.adminPageDatatable.handleRemove = function() {
    const self = this;
    $(document).delegate('.btn-delete-page', 'click', function() {
        self.removeSelected([{id: $(this).data('id')}], app.adminPageDatatable.table);
    });
};

app.adminPageDatatable.handleDuplicatePage = function() {
    const $self = this;
    $(document).delegate('.btn-duplicate-page', 'click', function() {
        const id = $(this).data('id');

        const page = $self.table.findItem('id', id);
        if (page) {
            const $modal = $('#duplicate-page-modal');
            $modal.find('[name="name"]').val(page.name);
            $modal.find('[name="description"]').val(page.description);
            $modal.find('form').data('route', '/admin/pages/'+page.id+'/duplicate');
            $modal.modal('show');
        }
    });
};

app.adminPageDatatable.handleMoveToDraft = function() {
    const self = this;
    $(document).delegate('.btn-move-to-draft-page', 'click', function() {
        self.moveToDraft([{id: $(this).data('id')}], app.adminPageDatatable.table);
    });
};

app.adminPageDatatable.handleMoveToPublished = function() {
    const self = this;
    $(document).delegate('.btn-move-to-published-page', 'click', function() {
        self.moveToPublished([{id: $(this).data('id')}], app.adminPageDatatable.table);
    });
};

app.adminPageDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminPageDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminPageDatatable.init = function() {
    this.initDatatable();
    this.handleRemove();
    this.handleMoveToDraft();
    this.handleMoveToPublished();
    this.handleDuplicatePage();
};

$(document).ready(app.adminPageDatatable.init());
