"use strict";

// define app.adminTicketsDatatable object
app.adminTicketsDatatable = {};

// handle app.adminTicketsDatatable object on deleting ticket with ajax request
app.adminTicketsDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans("You're about to delete ticket(s)!"),
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
                    url: app.adminTicketsDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminTicketsDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminTicketsDatatable object on restoring ticket with ajax request
app.adminTicketsDatatable.restore = function() {

    $(document).delegate('.btn-admin-tickets-restore', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to restore this ticket!"),
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
                        url: app.adminTicketsDatatable.config.restore.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminTicketsDatatable.tb.refresh();
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

// handle app.adminTicketsDatatable object on multi delete ajax request
app.adminTicketsDatatable.forceDelete = function() {

    $(document).delegate('.btn-admin-tickets-force-delete', 'click', function() {

        var button = $(this);
        var id = button.attr('data-id');

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to force delete this ticket!"),
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
                        url: app.adminTicketsDatatable.config.forceDelete.route,
                        data: {id:id},
                        beforeSend: function () {
                            app.buttonLoader(actionButton);
                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            app.adminTicketsDatatable.tb.refresh();
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

// handle app.adminTicketsDatatable object configuration
app.adminTicketsDatatable.config = {
    delete: {
        route: '/admin/tickets/multi-delete'
    },
    restore: {
        route: '/admin/tickets/restore'
    },
    forceDelete: {
        route: '/admin/tickets/force-delete'
    },
    datatable: {
        selectable: false,
        src: '/admin/tickets/datatable',
        resourceName: { singular: app.trans('ticket'), plural: app.trans('tickets') },
        columns: [     
            {
                title: '',
                key: 'avatar',
                classes: 'users-avatar-row',
                searchable: false,
                element: function(row) {
                    if(row.user !== null) {
                        var html = '<img src="'+app.adminUsersDatatable.avatar(row.user.avatar)+'" class="rounded-circle img-responsive" width="40">'
                        return $(html);
                    }
                    
                    return $('<span>&nbsp;</span>');
                }
            },    
            {
                title: app.trans('Number'),
                key: 'number',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Subject'),
                key: 'subject',
                searchable: true,
                hidden: true
            },    
            {
                title: app.trans('Full name'),
                key: 'user.first_name',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Email'),
                key: 'user.email',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Customer'),
                key: 'user.first_name',
                searchable: true,
                classes: 'tickets-user-column',
                element: function(row) {
                    var html = '';
                	if(row.user !== null) {
                        if(row.user.first_name !== null && row.user.last_name !== null) {
                            var html = '<span>'+row.user.first_name+' '+row.user.last_name+'</span>';
                            html += '<br>';
                        } else {
                            var html = '<span>'+row.user.email+'</span>';
                            html += '<br>';
                        }
                    }

                    if(row.deleted_at === null) {
                        html += '<a href="/admin/tickets/'+row.number+'/show"><span class="text-bold">'+row.subject + '</span></a>';
                    } else {
                        html += '<span class="text-bold">'+row.subject + '</span>';
                    }

                    html += '<br>';
                    html += '<span>[Ticket #' + row.number + ']</span>';
                    
                    return $(html);
                }
            },  
            {
                title: app.trans('Category'),
                key: 'category.name',
                searchable: true,
                classes: 'tickets-category-column',
                element: function(row) {
                	if(row.category !== null) {
                        var txtColor = '#fff';
                        var bgColor = row.category.color;

                        if(row.category.color === null) {
                            var txtColor = '#000';
                            var bgColor = '#ddd';
                        }

                        return $('<span class="badge" style="width: 100%; color: '+txtColor+'; background-color: '+bgColor+'">'+row.category.name+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }
            },          
            {
                title: app.trans('Priority'),
                key: 'priority',
                searchable: true,
                classes: 'tickets-priority-column',
                element: function(row) {
                    var status = row.priority_details;

                    if(status.name !== undefined) {
                        return $('<span class="badge badge-'+status.color+'" style="width: 100%;">'+status.name+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }
            },
            {
                title: app.trans('Status'),
                key: 'status',
                searchable: true,
                classes: 'tickets-status-column',
                element: function(row) {
                    var status = row.status_details;

                    if(status.name !== undefined) {
                        return $('<span class="badge badge-'+status.color+'" style="width: 100%;">'+status.name+'</span>');
                    }

                    return $('<span>&nbsp;</span>');
                }
            },
            {
                title: app.trans('Created'),
                key: 'created_at_timeago',
                searchable: false,
                classes: 'tickets-status-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'tickets-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {                            
                            if(app.can('admin.tickets.show')) {
                                html += '<a class="dropdown-item" href="/admin/tickets/'+row.number+'/show"><i class="fas fa-reply"></i> '+app.trans('Reply')+'</a>';
                            }
                        }
                            
                        if(row.deleted_at !== null) {
                            if(app.can('admin.tickets.restore')) {
                                html += '<a class="dropdown-item btn-admin-tickets-restore" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash-restore-alt"></i> ' + app.trans('Restore') + '</a>';
                            }

                            if(app.can('admin.tickets.force-delete')) {
                                html += '<a class="dropdown-item btn-admin-tickets-force-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Force delete') + '</a>';
                            }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No tickets were found!")
        },
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('All'), value: 'All' },
                    { label: app.trans('Open'), value: 'open' },
                    { label: app.trans('On Hold'), value: 'onhold' },
                    { label: app.trans('Closed'), value: 'closed' },
                    { label: app.trans('Trashed'), value: 'Trashed' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            },
            {
                key: 'priority',
                title: app.trans('Priority'),
                choices: [
                    { label: app.trans('High'), value: 'high' },
                    { label: app.trans('Medium'), value: 'medium' },
                    { label: app.trans('Low'), value: 'low' }
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
                { value: 'subject__asc', label: app.trans('Subject') + ' ( A - Z )' },
                { value: 'subject__desc', label: app.trans('Subject') + ' ( Z - A )' },
                { value: 'category__asc', label: app.trans('Category') + ' ( A - Z )' },
                { value: 'category__desc', label: app.trans('Category') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminTicketsDatatable.delete,
                status: 'error'
            },
        ],
        limit: 50
    }
};

// initialize functions of app.adminTicketsDatatable object
app.adminTicketsDatatable.init = function() {
    app.adminTicketsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined' && $("#admin-tickets-datatable").length) {

        var datatableConfig = this.config.datatable;

    
        var items = JSON.parse($("#admin-tickets-datatable").attr('data-status-counter'));
       
        var totalOpen = 0;
        var totalOnHold = 0;
        var totalClosed = 0;
        var totalTrashed = items['trashed'];
        var totalAll = items['all'];

        $.each(items['statuses'], function(key, item) {
            if(item.status == 'open') {
                totalOpen = item.total_status;
            }

            if(item.status == 'onhold') {
                totalOnHold = item.total_status;
            }

            if(item.status == 'closed') {
                totalClosed = item.total_status;
            }
        });

        datatableConfig['filterTabs'] = [
            { label: app.trans('All <span class=\'badge badge-primary\'>' + totalAll + '</span>'), filters: [] },
            { label: app.trans('Open <span class=\'badge badge-danger\'>' + totalOpen + '</span>'), filters: [{ key: 'status', value: 'open' }] },
            { label: app.trans('On Hold <span class=\'badge badge-warning\'>' + totalOnHold + '</span>'), filters: [{ key: 'status', value: 'onhold' }] },
            { label: app.trans('Closed <span class=\'badge badge-default\'>' + totalClosed + '</span>'), filters: [{ key: 'status', value: 'closed' }] },
            { label: app.trans('Trashed <span class=\'badge badge-info\'>' + totalTrashed + '</span>'), filters: [{ key: 'status', value: 'Trashed' }] }
        ];
        
        app.adminTicketsDatatable.tb = $("#admin-tickets-datatable").JsDataTable(datatableConfig);
    }

    // Restore trashed record
    app.adminTicketsDatatable.restore();
    // Force delete trashed record
    app.adminTicketsDatatable.forceDelete();
};

// initialize app.adminTicketsDatatable object until the document is loaded
$(document).ready(app.adminTicketsDatatable.init());