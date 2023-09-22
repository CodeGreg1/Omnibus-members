"use strict";

// define app.adminUsers object
app.adminUsers = {}

// handle on confirming user ajax request
app.adminUsers.confirm = function() {
    $('.btn-confirm-user').on('click', function() {
        var ids = [];
        var $button = $(this);
        var $content = $button.html();

        ids.push($(this).data('id'));

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans('Your about to confirm this user.'),
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
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            location.reload();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent($button, $content);
                        }
                    });
                }
            }
        });
    });
};

// handle on enabling user ajax request
app.adminUsers.enable = function() {
    $('.btn-enable-user').on('click', function() {
        var ids = [];
        var $button = $(this);
        var $content = $button.html();

        ids.push($(this).data('id'));

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans('Your about to enable this user.'),
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
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            location.reload();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent($button, $content);
                        }
                    });
                }
            }
        });
    });
};

// handle on banning user ajax request
app.adminUsers.ban = function() {
	$('.btn-ban-user').on('click', function() {
        var ids = [];
        var $button = $(this);
        var $content = $button.html();

        ids.push($(this).data('id'));

        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans('Your about to ban this user.'),
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
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            location.reload();
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent($button, $content);
                        }
                    });
                }
            }
        });
	});
};

// initialize functions of app.adminUsers object
app.adminUsers.init = function() {
    app.adminUsers.confirm();
    app.adminUsers.enable();
    app.adminUsers.ban();
};

// initialize app.adminUsers object until the document is loaded
$(document).ready(app.adminUsers.init());