"use strict";

// define app.adminEmailTemplatesDatatable object
app.adminEmailTemplatesDatatable = {};

// handle app.adminEmailTemplatesDatatable object delete ajax
app.adminEmailTemplatesDatatable.delete = function(selected) {

    const ids = selected.map(item => item.id);

    bootbox.confirm({
        title: app.trans("Are you sure?"),
        message: app.trans("Your about to delete email template(s)!"),
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
                    url: app.adminEmailTemplatesDatatable.config.delete.route,
                    data: {ids:ids},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                        app.adminEmailTemplatesDatatable.tb.refresh();
                    },
                    error: function (response) {
                        app.notify(response.responseJSON.message);
                    }
                });
            }
        }
    });
};

// handle app.adminEmailTemplatesDatatable object configuration datatable
app.adminEmailTemplatesDatatable.config = {
    delete: {
        route: '/admin/email-templates/delete'
    },
    datatable: {
        src: '/admin/email-templates/datatable',
        resourceName: { singular: app.trans("email template"), plural: app.trans("email templates") },
        columns: [
            {
                title: app.trans('Code'),
                key: 'code',
                classes: 'email-templates-code-column'
            },
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'email-templates-name-column'
            },
            {
                title: app.trans('Subject'),
                key: 'subject',
                classes: 'email-templates-subject-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'role-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        if(app.can('admin.email-templates.edit')) {
                            html += '<a class="dropdown-item" href="/admin/email-templates/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                        }
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        filterTabs: [
            { label: app.trans('All'), filters: [] }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No email templates were found!")
        },
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'name__asc', label: app.trans('Name ( A - Z )') },
                { value: 'name__desc', label: app.trans('Name ( Z - A )') }
            ]
        },
        bulkActions: [
            {
                title: app.trans("Delete selected email templates"),
                onAction: app.adminEmailTemplatesDatatable.delete,
                status: 'error'
            },
        ],
        limit: 25
    }
};

// call all available functions of app.adminEmailTemplatesDatatable object to initialize
app.adminEmailTemplatesDatatable.init = function() {
    app.adminEmailTemplatesDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminEmailTemplatesDatatable.tb = $("#email-templates-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminEmailTemplatesDatatable object until the document is loaded
$(document).ready(app.adminEmailTemplatesDatatable.init());