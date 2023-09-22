"use strict";

// define app.adminUserActivitiesDatatable object
app.adminUserActivitiesDatatable = {};

// handle app.adminUserActivitiesDatatable object configuration
app.adminUserActivitiesDatatable.config = {
    datatable: {
        selectable: false,
        src: '/admin/activities/datatable',
        resourceName: { singular: "activity", plural: "activities" },
        columns: [
            {
                title: app.trans('Activity'),
                key: 'description',
                classes: 'description-column',
                searchable: true,
                hidden: false,
                element: function(row) {

                    var html = '<span>';

                    if(row.causer != null) {
                        html += '<a href="/admin/users/'+ row.causer.id +'/show" target="_blank">';
                        
                        if(row.causer.full_name != 'N/A') {
                            html += row.causer.full_name +'</a> ';
                        } else {
                            html += app.trans('User with ID') + ': ' + row.causer.id +'</a> ';
                        }
                        
                    }

                    html += row.description;
                    html += ' &#9679; <i>' + row.created_at_for_humans + '</i></span>';
                    html += '<br><span>(<a href="https://whatismyipaddress.com/ip/'+row.properties.user_ip+'" target="_blank" rel="nofollow">'+row.properties.user_ip+'</a>)</span>';
                    
                    return $(html);
                }
            },
            {
                title: app.trans('User agent'),
                key: 'user_agent',
                classes: 'user-agent-column',
                searchable: false,
                hidden: false,
                element: function(row) {

                    if(row.properties.user_agent !== undefined) {
                        var html = '<span>' + row.properties.user_agent + '</span>';
                    } else {
                        var html = '<span>&nbsp;</span>';
                    }

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No user activities were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] }
        ],
        sortControl: {
            value: 'id__desc',
            options: [
                { value: 'id__desc', label: app.trans('Latest') },
                { value: 'id__asc', label: app.trans('Oldest') }
            ]
        },
        limit: 25
    }
};

// initialize functions of app.adminUserActivitiesDatatable object
app.adminUserActivitiesDatatable.init = function() {
    app.adminUserActivitiesDatatable.tb =  {};
    
    if(typeof $.fn.JsDataTable !== 'undefined') {

        app.adminUserActivitiesDatatable.tb = $("#admin-user-activities-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.adminUserActivitiesDatatable object until the document is loaded
$(document).ready(app.adminUserActivitiesDatatable.init());