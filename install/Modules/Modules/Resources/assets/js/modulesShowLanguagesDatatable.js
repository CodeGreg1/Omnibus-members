"use strict";

// define app.modulesLanguageDatatable object
app.modulesLanguageDatatable = {};

// handle app.modulesLanguageDatatable object configuration
app.modulesLanguageDatatable.config = {
    datatable: {
        selectable: false,
        src: '/admin/module/language-datatable',
        resourceName: { singular: app.trans("module language"), plural: app.trans("module languages") },
        columns: [        
            {
                title: app.trans('Title'),
                key: 'title',
                classes: 'languages-title-column',
                element: function(row) {
                    var html = '<span>' + row.title;
                    html += '</span>';

                    return $(html);
                }
            },          
            {
                title: app.trans('Active'),
                key: 'active',
                classes: 'module-language-active-column',
                element: function(row) {
                    var html = '<div class="custom-control custom-checkbox">';
                        html += '<input type="checkbox" class="custom-control-input" id="col-'+row.id+'" '+(row.active==1?'checked':'')+' disabled>';
                        html += '<label class="custom-control-label" for="col-'+row.id+'"></label>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'languages-actions-column tb-actions-column',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';
                            
                        if(row.deleted_at === null) 
                        {
                            var moduleId = $("#module-language-datatable").data('id');
                            
                            if(app.can('admin.modules.edit-language')) {
                               html += '<a class="dropdown-item" href="/admin/module/'+moduleId+'/edit/language/'+row.code+'"><i class="fas fa-edit"></i> ' + app.trans('Edit') + '</a>'; 
                           }
                        }

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No module languages were found!")
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
        limit: 10
    }
};

// initialize functions of app.modulesLanguageDatatable object
app.modulesLanguageDatatable.init = function() {
    app.modulesLanguageDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.modulesLanguageDatatable.tb = $("#module-language-datatable").JsDataTable(this.config.datatable);
    }
};

// initialize app.modulesLanguageDatatable object until the document is loaded
$(document).ready(app.modulesLanguageDatatable.init());