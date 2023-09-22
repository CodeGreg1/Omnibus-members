"use strict";

// define app.adminDashboardWidgetsDatatable object
app.adminDashboardWidgetsDatatable = {};

// handle app.adminDashboardWidgetsDatatable object with datatable configuration
app.adminDashboardWidgetsDatatable.config = {
    delete: {
        route: '/admin/dashboard-widgets/multi-delete'
    },
    datatable: {
        selectable: false,
        src: '/admin/dashboard-widgets/datatable',
        resourceName: { singular: app.trans('dashboard widget'), plural: app.trans('dashboard widgets') },
        columns: [      
            {
                title: '',
                key: 'handle',
                searchable: true,
                classes: 'dashboardwidgets-handle-column',
                element: function(row) {
                    var html = '<span data-id="'+row.id+'" data-ordering="'+row.ordering+'"><i class="fas fa-arrows-alt drag-handle"></i></span>';
                    
                    return $(html);
                }
            },  
            {
                title: app.trans('Id'),
                key: 'id',
                searchable: true,
                classes: 'dashboardwidgets-id-column tb-id-column'
            },          
            {
                title: app.trans('Name'),
                key: 'name',
                searchable: true,
                classes: 'dashboardwidgets-name-column'
            },           
            {
                title: app.trans('Type'),
                key: 'type',
                searchable: true,
                classes: 'dashboardwidgets-type-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'dashboardwidgets-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                                                        
                            if(app.can('admin.dashboard-widgets.edit')) {
                                html += '<a class="dropdown-item" href="/admin/dashboard-widgets/'+row.id+'/edit"><i class="fas fa-edit"></i> '+app.trans('Edit')+'</a>';
                            }
                            
                            if(app.can('admin.dashboard-widgets.delete')) {
                                html += '<a class="dropdown-item btn-admin-dashboardwidgets-delete" href="javascript:void(0)" data-id="'+row.id+'"><i class="fas fa-trash"></i> ' + app.trans('Delete') + '</a>';
                            }
                            

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No dashboard widgets were found!")
        },
        sortControl: {
            value: 'ordering__asc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') },
                { value: 'name__asc', label: app.trans('Name') + ' ( A - Z )' },
                { value: 'name__desc', label: app.trans('Name') + ' ( Z - A )' },
                { value: 'type__asc', label: app.trans('Type') + ' ( A - Z )' },
                { value: 'type__desc', label: app.trans('Type') + ' ( Z - A )' },
                { value: 'ordering__desc', label: app.trans('Ordering') + ' ( Z - A )' },
                { value: 'ordering__asc', label: app.trans('Ordering') + ' ( A - Z )' },
            ]
        },
        bulkActions: [
            
        ],
        limit: 50
    }
};

// initialize the dashboard widgets datatable
app.adminDashboardWidgetsDatatable.init = function() {
    app.adminDashboardWidgetsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminDashboardWidgetsDatatable.tb = $("#admin-dashboardwidgets-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminDashboardWidgetsDatatable object until the document is loaded
$(document).ready(app.adminDashboardWidgetsDatatable.init());