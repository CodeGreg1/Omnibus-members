"use strict";

app.adminBlogsDatatable = {};

app.adminBlogsDatatable.removeSelected = function(selected, table) {
    const blogs = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to delete blog post(s)!"),
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
                app.adminBlogsDatatable.tableAjax(
                    'delete',
                    {blogs},
                    '/admin/blogs/destroy',
                    table
                );
            }
        }
    });
};

app.adminBlogsDatatable.moveToPending = function(selected, table) {
    const blogs = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to move selected blog post(s) to pending!"),
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
                app.adminBlogsDatatable.tableAjax(
                    'post',
                    {blogs},
                    '/admin/blogs/move-to-pending',
                    table
                );
            }
        }
    });
};

app.adminBlogsDatatable.moveToDraft = function(selected, table) {
    const blogs = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to move selected blog post(s) to draft!"),
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
                app.adminBlogsDatatable.tableAjax(
                    'post',
                    {blogs},
                    '/admin/blogs/move-to-draft',
                    table
                );
            }
        }
    });
};

app.adminBlogsDatatable.moveToPublished = function(selected, table) {
    const blogs = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("You're about to move selected blog post(s) to published!"),
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
                app.adminBlogsDatatable.tableAjax(
                    'post',
                    {blogs},
                    '/admin/blogs/move-to-published',
                    table
                );
            }
        }
    });
};

app.adminBlogsDatatable.tableAjax = function(method, data, route, table) {
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

app.adminBlogsDatatable.config = {
    datatable: '#admin-blogs-datatable',
    route: '/admin/blogs/datatable',
    options: {
        src: '/admin/blogs/datatable',
        resourceName: { singular: app.trans("blog"), plural: app.trans("blogs") },
        columns: [
            {
                title: app.trans('Title'),
                key: 'title',
                classes: 'blog-title-row'
            },
            {
                title: app.trans('Category'),
                key: 'category',
                classes: 'blog-category-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<span>${ row.category.name }</span>`);
                }
            },
            {
                title: app.trans('Author'),
                key: 'author',
                classes: 'blog-author-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<a href="/admin/users/${row.author.id}/overview">
                              <img alt="image" src="${row.author.avatar}" class="rounded-circle" width="35" data-toggle="title" title=""> <div class="d-inline-block ml-1">${ row.author.full_name || row.author.email }</div>
                        </a>`);
                }
            },
            {
                title: app.trans('Created'),
                key: 'created',
                classes: 'blog-created-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Status'),
                key: 'status',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var label = row.status.charAt(0).toUpperCase() + row.status.slice(1);
                    var html = '<span class="badge badge-'+row.status_color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'deposit-actions-column tb-actions-column text-right',
                element: function(row) {
                    var statuses = ['pending', 'draft', 'published'];
                    var index = statuses.indexOf(row.status);
                    if (index !== -1) {
                        statuses.splice(index, 1);
                    }
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        if (row.status === 'published') {
                            html += '<a class="dropdown-item" href="/blogs/'+row.slug+'" target="_blank">'+app.trans('View blog post in site')+'</a>';
                        }
                        html += '<a class="dropdown-item" href="/admin/blogs/'+row.id+'/edit">'+app.trans('Edit blog post')+'</a>';
                        for (let index = 0; index < statuses.length; index++) {
                            const status = statuses[index];
                           html += '<a class="dropdown-item btn-move-to-'+status+'-blog-post blog-post-status-transition-btn" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Move to :status', {status})+'</a>';
                        }
                        html += '<a class="dropdown-item text-danger btn-delete-blog-post" data-id="'+row.id+'" href="javascript:void(0)">'+app.trans('Delete blog post')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No blogs were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Published'), value: 'Published' },
                    { label: app.trans('Pending'), value: 'Pending' },
                    { label: app.trans('Draft'), value: 'Draft' },
                ],
                hidden: true,
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [] },
            { label: app.trans('Published'), filters: [{ key: 'status', value: 'Published' }] },
            { label: app.trans('Pending'), filters: [{ key: 'status', value: 'Pending' }] },
            { label: app.trans('Draft'), filters: [{ key: 'status', value: 'Draft' }] }
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
                title: app.trans("Removed selected"),
                onAction: app.adminBlogsDatatable.removeSelected.bind(app.adminBlogsDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Move to Pending"),
                onAction: app.adminBlogsDatatable.moveToPending.bind(app.adminBlogsDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Move to Draft"),
                onAction: app.adminBlogsDatatable.moveToDraft.bind(app.adminBlogsDatatable),
                reloadOnChange: true,
            },
            {
                title: app.trans("Move to Published"),
                onAction: app.adminBlogsDatatable.moveToPublished.bind(app.adminBlogsDatatable),
                reloadOnChange: true,
            }
        ],
        limit: 25
    }
};

app.adminBlogsDatatable.handleMoveToPending = function() {
    const self = this;
    $(document).delegate('.btn-move-to-pending-blog-post', 'click', function() {
        self.moveToPending([{id: $(this).data('id')}], app.adminBlogsDatatable.table);
    });
};

app.adminBlogsDatatable.handleMoveToDraft = function() {
    const self = this;
    $(document).delegate('.btn-move-to-draft-blog-post', 'click', function() {
        self.moveToDraft([{id: $(this).data('id')}], app.adminBlogsDatatable.table);
    });
};

app.adminBlogsDatatable.handleMoveToPublished = function() {
    const self = this;
    $(document).delegate('.btn-move-to-published-blog-post', 'click', function() {
        self.moveToPublished([{id: $(this).data('id')}], app.adminBlogsDatatable.table);
    });
};

app.adminBlogsDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminBlogsDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminBlogsDatatable.handleRemove = function() {
    const self = this;
    $(document).delegate('.btn-delete-blog-post', 'click', function() {
        self.removeSelected([{id: $(this).data('id')}], app.adminBlogsDatatable.table);
    });
};

app.adminBlogsDatatable.init = function() {
    this.initDatatable();
    this.handleRemove();
    this.handleMoveToPending();
    this.handleMoveToDraft();
    this.handleMoveToPublished();
};

$(document).ready(app.adminBlogsDatatable.init());
