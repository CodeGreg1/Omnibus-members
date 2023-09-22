"use strict";

// define app.adminUsersDatatable object
app.adminUsersDatatable = {};

// handle has subscription
app.adminUsersDatatable.hasSubscription = function() {
    return $("#users-datatable").attr('data-has-subscription');
};

// handle has wallet
app.adminUsersDatatable.hasWallet = function() {
    return $("#users-datatable").attr('data-has-wallet');
};

// handle subscription filter config
app.adminUsersDatatable.subscriptionFilterControler = function() {
    return {
        key: 'subscriptions',
        title: app.trans('Subscription'),
        choices: [
            { label: app.trans('None'), value: 'no_subscription' },
            { label: app.trans('Trialing'), value: 'trialing' },
            { label: app.trans('Active'), value: 'active' },
            { label: app.trans('Past Due'), value: 'past_due' },
            { label: app.trans('Cancelled'), value: 'cancelled' },
            { label: app.trans('Ended'), value: 'ended' }
        ],
        shortcut: true,
        allowMultiple: false,
        showClear: false,
        value: ''
    };
}

// handle wallet filter config
app.adminUsersDatatable.walletFilterControler = function() {
    return {
        key: 'wallet',
        title: app.trans('Wallet'),
        choices: [
            { label: app.trans('Has Balance'), value: 'has_balance' },
            { label: app.trans('No Balance'), value: 'no_balance' }
        ],
        shortcut: true,
        allowMultiple: false,
        showClear: true,
        value: ''
    };
};

// handle deleting user ajax request
app.adminUsersDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans('Your about to delete user(s).'),
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
                    url: app.adminUsersDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminUsersDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle multi ban ajax request
app.adminUsersDatatable.multiBan = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans('Your about to ban user(s).'),
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
                    type: 'patch',
                    url: app.adminUsersDatatable.config.multiBan.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminUsersDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle mutli enable users ajax request
app.adminUsersDatatable.multiEnable = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans('Your about to enable user(s).'),
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
                    type: 'patch',
                    url: app.adminUsersDatatable.config.multiEnable.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminUsersDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle multi confirm users ajax request
app.adminUsersDatatable.multiConfirm = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans('Are you sure?'),
        message: app.trans('Your about to confirm user(s).'),
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
                    type: 'patch',
                    url: app.adminUsersDatatable.config.multiConfirm.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminUsersDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle getting user avatar
app.adminUsersDatatable.avatar = function(avatar) {
    if (avatar === null) {
        return app.config.avatarDefault;
    }
    return avatar;
};

// handle datatable & routes configuration
app.adminUsersDatatable.config = {
    delete: {
        route: '/admin/users/delete'
    },
    multiBan: {
        route: '/admin/users/multi-ban'
    },
    multiEnable: {
        route: '/admin/users/multi-enable'
    },
    multiConfirm: {
        route: '/admin/users/multi-confirm'
    },
    datatable: {
        src: '/admin/users/datatable',
        resourceName: { singular: "user", plural: "users" },
        columns: [
            {
                title: '',
                key: 'avatar',
                classes: 'users-avatar-row',
                searchable: false,
                element: function(row) {
                    var html = '<img src="'+app.adminUsersDatatable.avatar(row.avatar)+'" class="rounded-circle img-responsive" width="40">'
                    return $(html);
                }
            },
            {
                title: app.trans('Name'),
                key: 'full_name',
                searchable: false,
                classes: '',
                element: function (row) {
                    var html = '<p style="margin-bottom: 0;">';

                    if (row.full_name != " ") {
                        html += '<span style="font-weight: bold;"><a href="/admin/users/'+row.id+'/overview">'+row.full_name+'</a></span><br>';
                    } else {
                        html += '<span style="font-weight: bold;"><a href="/admin/users/'+row.id+'/overview">'+app.trans('N/A')+'</a></span><br>';
                    }
                    
                    html += '<span class="text-muted">'+row.email+'</span><br>';

                    if (row.username !== null) {
                        html += '<span class="text-muted">'+row.username+'</span>';
                    }
                    
                    html += '</p>';

                    return $(html);
                }
            },
            {
                title: app.trans('First Name'),
                key: 'first_name',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Last Name'),
                key: 'last_name',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Email'),
                key: 'email',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Username'),
                key: 'username',
                searchable: true,
                hidden: true
            },
            {
                title: app.trans('Last Login'),
                key: 'last_login_for_humans',
                searchable: false,
                classes: 'users-last-login-row'
            },
            {
                title: app.trans('Joined'),
                key: 'created_at_for_humans',
                searchable: false,
                classes: 'users-joined-row'
            },
            {
                title: app.trans('Status'),
                key: 'status',
                searchable: false,
                classes: 'users-status-row',
                element: function(row) {
                    var label = 'badge-success';
                    var status = row.status;

                    if(row.status != 'Active') {
                        label = 'badge-danger';
                    }

                    if(row.email_verified_at === null && row.status == "Active") {
                        label = 'badge-warning';
                        status = 'Unconfirmed';
                    }                    

                    var html = '<span class="badge '+label+'">'+status+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'users-edit-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';

                        if(app.can('admin.users.edit')) {
                            html += '<a class="dropdown-item" href="/admin/users/'+row.id+'/edit/user-information"><i class="fas fa-edit"></i> '+app.trans('Edit user information')+'</a>';
                        }
                        
                        if(app.can('admin.users.edit-user-settings')) {
                            html += '<a class="dropdown-item" href="/admin/users/'+row.id+'/edit/user-settings"><i class="fas fa-edit"></i> '+app.trans('Edit user settings')+'</a>';
                        }

                        if(app.can('admin.users.billing')) {
                            html += '<a class="dropdown-item" href="/admin/users/'+row.id+'/billing"><i class="fas fa-edit"></i> '+app.trans('Billing & Plan')+'</a>';
                        }
                        
                        if(row.can_be_impersonated) {
                            html += '<a class="dropdown-item" href="/admin/users/'+row.id+'/impersonate"><i class="fas fa-user-secret"></i> '+app.trans('Impersonate')+'</a>';
                        }
                        
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No users were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] },
            { label: app.trans('Active'), filters: [{ key: 'status', value: 'Active' }] },
            { label: app.trans('Banned'), filters: [{ key: 'status', value: 'Banned' }] },
            { label: app.trans('Unconfirmed'), filters: [{ key: 'status', value: 'Unconfirmed' }] }
        ],
        filterControl: [
            {
                key: 'status',
                title: app.trans('Status'),
                choices: [
                    { label: app.trans('Active'), value: 'Active' },
                    { label: app.trans('Banned'), value: 'Banned' },
                    { label: app.trans('Unconfirmed'), value: 'Unconfirmed' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            },
            {
                key: 'role',
                title: app.trans('Role'),
                choices: [],
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
                { value: 'first_name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'first_name__desc', label: app.trans('Name') + ' ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans('Delete selected'),
                onAction: app.adminUsersDatatable.delete
            },
            {
                title: app.trans('Enable selected'),
                onAction: app.adminUsersDatatable.multiEnable
            },
            {
                title: app.trans('Ban selected'),
                onAction: app.adminUsersDatatable.multiBan
            },
            {
                title: app.trans('Confirm selected'),
                onAction: app.adminUsersDatatable.multiConfirm
            }
        ],
        limit: 25
    }
};

// initialize functions of app.adminUsersDatatable object
app.adminUsersDatatable.init = function() {
    app.adminUsersDatatable.tb =  {};
    
    if(typeof $.fn.JsDataTable !== 'undefined') {

        var datatableConfig = this.config.datatable;
        
        if(app.adminUsersDatatable.hasSubscription() == 1) {
            datatableConfig['filterControl'].push(app.adminUsersDatatable.subscriptionFilterControler())
        }

        if (app.adminUsersDatatable.hasWallet() == 1) {
            datatableConfig['filterControl'].push(app.adminUsersDatatable.walletFilterControler())
        }

        app.adminUsersDatatable.tb = $("#users-datatable").JsDataTable(datatableConfig);

        // check if #users-datatable exists
        if($("#users-datatable").length) {
            $.ajax({
                type: 'GET',
                url: '/admin/users/all-roles',
                success: function (response, textStatus, xhr) {

                    var roles = [];

                    $.each(response, function(key, role) {
                        var item = {}
                        item ["label"] = role.display_name;
                        item ["value"] = role.id;
                        roles.push(item);
                    });

                    app.adminUsersDatatable.tb.loadFilters('role', roles);
                }
            });
        }
    }
};

// initialize app.adminUsersDatatable object until the document is loaded
$(document).ready(app.adminUsersDatatable.init());