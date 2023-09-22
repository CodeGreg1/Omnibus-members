"use strict";

app.packageExtraConditionDatatable = {};

app.packageExtraConditionDatatable.config = {
    listContainer: '#package-extra-condition-table',
    items: [],
    noItemHtml: '<div class="w-100 d-flex d-flex justify-content-center align-items-center" style="height: 200px;"><div class="d-flex flex-column align-items-center"><i class="fas fa-tags mb-1" style="font-size: 20px;"></i><p style="font-size: 16px;">'+app.trans('No extra conditions set')+'</p></div></div>',
    removeBtn: '.btn-delete-package-extra-condition-item'
};

app.packageExtraConditionDatatable.getRoute = function() {
    var id = $(this.config.listContainer).data('id');
    return '/admin/subscriptions/packages/' + id + '/extra-conditions/datatable';
};

app.packageExtraConditionDatatable.loader = function() {
    var html = '<div class="w-100" style="height: 200px;">';
    html += '<div class="h-100 d-flex justify-content-center align-items-center">';
    html += '<a href="#" class="btn disabled btn-secondary btn-progress">'+app.trans('Progress')+'</a>';
    html += '</div></div>';
    return html;
};

app.packageExtraConditionDatatable.loadTable = function(items) {
    this.config.items = items;
    var container = $(this.config.listContainer);
    container.html('');
    if (!items.length) {
        container.html(this.config.noItemHtml);
        return;
    }

    var table = $('<table class="table">');
    container.append(table);
    table.append('<thead><tr><th>'+app.trans('Name')+'</th><th>'+app.trans('Shortcode')+'</th><th>'+app.trans('Value')+'</th><th style="width: 80px;"></th></tr></thead><tbody></tbody>');
    for (let index = 0; index < items.length; index++) {
        const item = items[index];
        var tr = $('<tr>');
        tr.append('<td>'+item.name+'</td>');
        tr.append('<td>'+item.shortcode+'</td>');
        tr.append('<td>'+item.value+'</td>');
        tr.append('<td><div class="d-flex"><button type="button" class="btn btn-icon text-success btn-edit-package-extra-condition-item" data-id="'+item.id+'"><i class="fas fa-pencil-alt"></i></button><button type="button" class="btn btn-icon text-danger btn-delete-package-extra-condition-item" data-route="/admin/subscriptions/packages/'+item.package_id+'/extra-conditions/'+item.id+'/delete"><i class="fas fa-trash-alt"></i></button></div></td>');
        table.find('tbody').append(tr);
    }
};

app.packageExtraConditionDatatable.delete = function() {
    var self = this;
    $(document).delegate(self.config.removeBtn, 'click', function() {
        var button = $(this);
        const route = button.data('route');

        if (route) {
            bootbox.confirm({
                title: app.trans("Are you sure?"),
                message: app.trans("Your about to remove package extra condition."),
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
                        var dialogRemovePackagePriceGateway = bootbox.dialog({
                            message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Removing resource')+'...</p>',
                            closeButton: false
                        });

                        $.ajax({
                            type: 'DELETE',
                            url: route,
                            success: function (response, textStatus, xhr) {
                                app.notify(response.message);
                                setTimeout(function() {
                                    self.getItems();
                                    dialogRemovePackagePriceGateway.modal('hide');
                                }, 350);
                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown) {
                                var response = XMLHttpRequest;
                                setTimeout(function() {
                                    app.notify(response.responseJSON.message);
                                    dialogRemovePackagePriceGateway.modal('hide');
                                });
                                // Check check if form validation errors
                            }
                        });
                    }
                }
            });
        }
    });
};

app.packageExtraConditionDatatable.getItems = function() {
    var self = this;

    $.ajax({
        type: 'GET',
        url: self.getRoute(),
        beforeSend: function () {
            $(self.config.listContainer).html(self.loader(true));
        },
        success: function (response, textStatus, xhr) {
            self.loadTable(response.data);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            $(self.config.pricesContainer).html('');
        }
    });
};

app.packageExtraConditionDatatable.init = function() {
    if ($(this.config.listContainer).length) {
        this.getItems();
    }

    this.delete();
}

$(document).ready(app.packageExtraConditionDatatable.init());
