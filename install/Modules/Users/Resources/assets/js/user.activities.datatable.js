"use strict";

// define app.userActivitiesDatatable object
app.userActivitiesDatatable = {};

// handle app.userActivitiesDatatable object configuration
app.userActivitiesDatatable.config = {
    datatable: {
        selectable: false,
        src: '/profile/activities/datatable',
        resourceName: { singular: "activity", plural: "activities" },
        columns: [
            {
                title: app.trans('Activity'),
                key: 'description',
                searchable: true,
                hidden: false,
                element: function(row) {

                    var html = '<span>';

                    html += '<b>'+app.trans('You')+' </b>';

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

// initialize functions of app.userActivitiesDatatable object
app.userActivitiesDatatable.init = function() {
    app.userActivitiesDatatable.tb =  {};
    
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.userActivitiesDatatable.tb = $("#user-activities-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.userActivitiesDatatable object until the document is loaded
$(document).ready(app.userActivitiesDatatable.init());