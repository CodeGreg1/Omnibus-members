"use strict";

app.cartPromoCodeDatatable = {};

app.cartPromoCodeDatatable.openCreateModal = function() {
    $('#create-coupon-promo-code-modal').modal('show');
};

app.cartPromoCodeDatatable.multiActivate = function(selected, table) {
    var self = this;
    var data = selected.filter(item => !item.active).map(item => item.id);

    if (data.length) {
        $.ajax({
            type: 'POST',
            url: self.config.route.activate,
            data: {id: data},
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                table.refresh();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                console.log(response)
            }
        });
    }
};

app.cartPromoCodeDatatable.multiArchive = function(selected, table) {
    var self = this;
    var data = selected.filter(item => item.active).map(item => item.id);
    if (data.length) {
        if (data.length) {
        $.ajax({
            type: 'POST',
            url: self.config.route.archive,
            data: {id: data},
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                table.refresh();
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                console.log(response)
            }
        });
    }
    }
};

app.cartPromoCodeDatatable.newCode = function() {
    console.log(4)
};

app.cartPromoCodeDatatable.config = {
    datatable: '#promo-codes-datatable',
    route: {
        activate: window.location.pathname + '/promo-codes/activate',
        archive: window.location.pathname + '/promo-codes/archive',
    },
    options: {
        src: window.location.pathname + '/promo-codes',
        resourceName: { singular: "code", plural: "codes" },
        columns: [
            {
                title: 'Code',
                key: 'code',
                classes: 'coupon-code-column',
                element: function(row) {
                    var html = '<div class="d-flex align-items-center">';
                    html += '<a href="/admin/coupons/promo-codes/'+row.id+'">'
                    html += '<strong>'+row.code+'</strong>';
                    html += '</a>';
                    if (!row.active) {
                        html += '<span class="ml-3 badge bg-secondary">Archived</span>';
                    }
                    html += '</div>';
                    return $(html);
                }
            },
            {
                title: 'Redemptions',
                key: 'times_redeemed',
                classes: 'hidden md:table-cell',
                element: function(row) {
                    var text = row.times_redeemed;
                    if (row.max_redemptions) {
                        text += '/' + row.max_redemptions;
                    }
                    return $('<p>'+text+'</p>');
                }
            },
            {
                title: '',
                key: 'actions',
                searchable: false,
                classes: 'menu-actions-column tb-actions-column text-right',
                element: function(row) {
                    var html = '<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        html += '<i class="fas fa-ellipsis-h"></i>';
                    html += '</button>';
                    html += '<div class="dropdown-menu">';
                        html += '<a class="dropdown-item text-danger btn-delete-promo-code" data-route="/admin/coupons/promo-codes/'+row.id+'/delete" href="#">'+app.trans('Delete promo code')+'</a>';
                    html += '</div>';

                    return $(html);
                }
            },
            {
                title: 'max_redemptions',
                key: 'max_redemptions',
                hidden: true
            },
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No promo codes were found!")
        },
        buttons: [
            {
                label: app.trans('New Code'),
                action: app.cartPromoCodeDatatable.openCreateModal,
                style: 'primary'
            }
        ],
        selectable: false
    }
};

app.cartPromoCodeDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.cartPromoCodeDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.cartPromoCodeDatatable.init = function() {
    this.initDatatable();
}

$(document).ready(app.cartPromoCodeDatatable.init());
