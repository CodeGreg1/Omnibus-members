app.packageDatatable = {};

app.packageDatatable.deletePackages = function(selected) {
    if (selected.length) {
        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("Your about to remove selected packages."),
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
                    var dialogRemovePackagesGateway = bootbox.dialog({
                        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+ app.trans('Removing resources') + '...</p>',
                        closeButton: false
                    });

                    $.ajax({
                        type: 'POST',
                        url: app.packageDatatable.config.destroyPackagesRoute,
                        data: {packages: selected.map(item => item.id)},
                        beforeSend: function () {

                        },
                        success: function (response, textStatus, xhr) {
                            app.notify(response.message);
                            setTimeout(function() {
                                dialogRemovePackagesGateway.modal('hide');
                                app.packageDatatable.datatable.refresh();
                            }, 350);
                        },
                        complete: function (xhr) {

                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            var response = XMLHttpRequest;
                            // Check check if form validation errors
                            setTimeout(function() {
                                dialogRemovePackagesGateway.modal('hide');
                                bootbox.alert(response.responseJSON.message);
                            }, 500);
                        }
                    });
                }
            }
        });
    }
};

app.packageDatatable.config = {
    enablePackagesRoute: '/admin/subscriptions/packages/enable',
    disablePackagesRoute: '/admin/subscriptions/packages/disable',
    destroyPackagesRoute: '/admin/subscriptions/packages/delete',
    datatable: {
        src: '/admin/subscriptions/packages/datatable',
        resourceName: { singular: app.trans("package"), plural: app.trans("packages") },
        columns: [
            {
                title: app.trans('Name'),
                key: 'name',
                classes: 'w-75',
                element: function(row) {
                    let html = '<div class="d-flex flex-column">';
                    html += '<a href="/admin/subscriptions/packages/'+row.id+'" class="fw-bolder"><strong>'+row.name+'</strong></a>';
                    html += '<span>'+row.description+'</span>';
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: app.trans('Created On'),
                key: 'created_at',
                classes: 'hidden md:table-cell',
                searchable: false,
                style: {
                    'width': '160px'
                },
                element: function(row) {
                    return $('<span>'+ row.created +'</span>')
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                classes: 'package-action-column text-right',
                searchable: false,
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item" href="/admin/subscriptions/packages/'+row.id+'"><i class="fas fa-eye"></i> View</a>';
                        html += '<a class="dropdown-item text-danger btn-delete-package" href="#" data-href="/admin/subscriptions/packages/delete?packages='+row.id+'"><i class="fas fa-trash"></i> Delete</a>';
                    html += '</div>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No packages were found!")
        },
        sortControl: {
            value: 'created_at__desc',
            options: [
                { value: 'created_at__desc', label: 'Latest Created' },
                { value: 'created_at__asc', label: 'Oldest Created' },
                { value: 'name__asc', label: 'Name ( A - Z )' },
                { value: 'name__desc', label: 'Name ( Z - A )' }
            ]
        },
        bulkActions: [
            {
                title: app.trans("Delete selected packages"),
                onAction: app.packageDatatable.deletePackages,
                status: 'error'
            },
        ],
        limit: 25
    }
};

app.packageDatatable.loadAjaxheaders = function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content") }
    });
};

app.packageDatatable.sections = function() {
    $('.package-sections a').click(function(){
        $('.package-sections a').removeClass("active");
        $(this).addClass("active");
    });
};

app.packageDatatable.sticky = function(e) {
    // TODO: Must add documentation about checking plugin if using global
    if(typeof $.fn.stickySidebar !== 'undefined') {
        $('.sidebar-item').stickySidebar({
            topSpacing: 0,
            container: '.wrapper-main',
            sidebarInner: '.make-me-sticky'
        });
    }
};

app.packageDatatable.init = function() {
    this.loadAjaxheaders();
    // this.sticky();
    // this.sections();
    this.datatable = false;
    if(typeof $.fn.JsDataTable !== 'undefined' && $('#packages-datatable').length) {
        this.datatable = $('#packages-datatable').JsDataTable(this.config.datatable);
    }
}

app.packageDatatable.init()
