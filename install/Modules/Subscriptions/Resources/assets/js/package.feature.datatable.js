app.packageFeatureDatatable = {};

app.packageFeatureDatatable.config = {
    datatable: {
        selectable: false,
        src: '/admin/subscriptions/packages/features/datatable',
        resourceName: { singular: app.trans('feature'), plural: app.trans('features') },
        columns: [
            {
                title: '',
                key: 'handle',
                searchable: false,
                orderable: false,
                classes: 'package-feature-handle-column',
                element: function(row) {
                    var html = '<span data-id="'+row.id+'" data-ordering="'+row.ordering+'"><i class="fas fa-arrows-alt drag-handle"></i></span>';

                    return $(html);
                }
            },
            {
                title: app.trans('Title'),
                key: 'title',
                searchable: true,
                classes: 'package-feature-title-column tb-title-column'
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'package-features-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                        html += '</button>';
                        html += '<div class="dropdown-menu">';

                            html += '<a href="javascript:void(0)" class="dropdown-item btn-edit-feature" data-title="'+row.title+'" data-description="'+(row.description || '')+'" data-route="/admin/subscriptions/packages/features/'+row.id+'/update">Edit</a>';

                            html += '<a href="javascript:void(0)" class="dropdown-item text-danger btn-delete-feature" data-route="/admin/subscriptions/packages/features/'+row.id+'/delete">Delete</a>';

                        html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No features were found!")
        },
        sortControl: {
            value: 'ordering__asc',
            options: [

            ]
        },
        sortValue: "ordering__asc",
        limit: 50
    }
};

app.packageFeatureDatatable.sort = function() {
    $("#admin-package-features-datatable table td").each(function () {
            $(this).css("width", $(this).outerWidth());
        });
        $( "#admin-package-features-datatable tbody" ).sortable({
            delay: 150,
            handle: '.drag-handle',
            cursor: 'move',
            placeholder:'must-have-class',
            stop: function() {
                var dataOrdering = new Array();
                $('#admin-package-features-datatable tbody>tr').each(function() {
                    var row = $(this);
                    var handleCol = row.find('.package-feature-handle-column');
                    var id = handleCol.find('>span').attr('data-id');
                    var ordering = handleCol.find('>span').attr('data-ordering');

                    dataOrdering.push({
                        id: id,
                        ordering: ordering
                    });
                });

                $.ajax({
                    type: 'POST',
                    url:"/admin/subscriptions/packages/features/reorder",
                    dataType: "json",
                    data: {ordering: dataOrdering},
                    success:function(response) {
                        app.notify(response.message);
                        app.packageFeatureDatatable.tb.refresh();
                    }
                })
            }
        });
};

app.packageFeatureDatatable.init = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $("#admin-package-features-datatable").length) {
        app.packageFeatureDatatable.tb = $("#admin-package-features-datatable").JsDataTable(this.config.datatable);
    }

    if(typeof $.fn.sortable !== 'undefined') {
        app.packageFeatureDatatable.sort();
    }
};

$(document).ready(app.packageFeatureDatatable.init());
