"use strict";

app.userDepositHistoryDatatable = {};

app.userDepositHistoryDatatable.config = {
    datatable: '#user-deposit-histories-datatable',
    route: '/user/deposits/histories/datatable',
    options: {
        src: '/user/deposits/histories/datatable',
        resourceName: { singular: app.trans("deposit history"), plural: app.trans("deposit histories") },
        columns: [
            {
                title: app.trans('Transaction ID'),
                key: 'trx',
                classes: 'deposit-history-trx-row'
            },
            {
                title: app.trans('Gateway'),
                key: 'gateway',
                classes: 'deposit-history-gateway-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Amount'),
                key: 'total',
                classes: 'deposit-history-total-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'deposit-history-date-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Status'),
                key: 'active',
                classes: 'hidden md:table-cell',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var color = 'secondary';
                    var label = app.trans('Pending');
                    if (row.rejected_at) {
                        color = 'danger';
                        label = app.trans('Rejected');
                    } else {
                        if (row.status) {
                            color = 'primary';
                            label = app.trans('Completed');
                        }
                    }

                    var html = '<span class="badge badge-'+color+'">'+label+'</span>';
                    return $(html);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'manual-gateway-actions-column tb-actions-column text-right',
                element: function(row) {
                    return $(`<a href="javascript:void(0)" class="btn btn-primary btn-sm btn-deposit-request-details-show" data-id="${row.id}"><i class="fas fa-eye"></i></a>`);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No deposit histories were found!")
        },
        selectable: false,
        showSearchQuery: false,
        limit: 25
    }
};

app.userDepositHistoryDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-deposit-request-details-show', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.userDepositHistoryDatatable.table.findItem('id', parseInt(id));
        if (item) {
            var message = `<ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Transaction ID')}</span>
                                <span>
                                    ${item.trx}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Date')}</span>
                                <span>
                                    ${item.date}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Gateway')}</span>
                                <span>
                                    ${item.gateway_name}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Amount')}</span>
                                <span>
                                    ${item.total}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Charge')}</span>
                                <span>
                                    ${item.charge_display}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Payable')}</span>
                                <span>
                                    ${item.payable_display}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Status')}</span>
                                <strong class="${item.status ? 'text-success' : (item.rejected_at ? 'text-danger' : '')}">
                                    ${item.status ? 'Approved' : (item.rejected_at ? 'Rejected' : 'Pending')}
                                </strong>
                            </div>
                        </li>`;
            if (item.rejected_at) {
                message += `<li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Rejected date')}</span>
                                <span>
                                    ${item.rejected_date}
                                </span>
                            </div>
                        </li>`;
                if (item.reject_reason) {
                    message += `<li class="list-group-item">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>${app.trans('Rejected reason')}</span>
                                <span>
                                    ${item.reject_reason}
                                </span>
                            </div>
                        </li>`;
                }
            }
            message += '</ul>'

            var dialog = bootbox.dialog({
                title: app.trans('Deposit details'),
                message: message,
                buttons: {
                    cancel: {
                        label: app.trans("Close"),
                        className: 'btn-danger'
                    }
                }
            });
            // $('.checkout-details-amount').html(item.total);
            // $('.checkout-details-charge').html(item.charge_display);
            // $('.checkout-details-payable').html(item.payable_display);
        }
    });
};

app.userDepositHistoryDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.userDepositHistoryDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.userDepositHistoryDatatable.init = function() {
    this.initDatatable();
    this.initShowDetails();
};

$(document).ready(app.userDepositHistoryDatatable.init());
