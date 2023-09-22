"use strict";

app.userWithdrawHistoryDatatable = {};

app.userWithdrawHistoryDatatable.config = {
    datatable: '#user-withdraw-histories-datatable',
    options: {
        src: '/user/withdrawals/histories/datatable',
        resourceName: { singular: app.trans("withdraw history"), plural: app.trans("withdraw histories") },
        columns: [
            {
                title: app.trans('Transaction ID'),
                key: 'trx',
                classes: 'withdraw-history-trx-row'
            },
            {
                title: app.trans('Method'),
                key: 'method',
                classes: 'withdraw-history-method-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Amount'),
                key: 'total',
                classes: 'withdraw-history-amount-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Charge'),
                key: 'charge_display',
                classes: 'withdraw-history-charge-row',
                searchable: false,
                orderable: false
            },
            {
                title: app.trans('Receivable'),
                key: 'receivable',
                classes: 'withdraw-history-receivable-row',
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
                classes: 'withdrawal-actions-column tb-actions-column text-right',
                element: function(row) {
                    return $(`<a href="javascript:void(0)" class="btn btn-primary btn-sm btn-withdrawal-request-details-show" data-id="${row.id}"><i class="fas fa-eye"></i></a>`);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No withdrawal histories were found!")
        },
        selectable: false,
        showSearchQuery: false,
        limit: 25
    }
};

app.userWithdrawHistoryDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-withdrawal-request-details-show', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.userWithdrawHistoryDatatable.table.findItem('id', parseInt(id));
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
                                    ${item.method}
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
                                <span>${app.trans('Receivable')}</span>
                                <span>
                                    ${item.receivable}
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

            message += '</ul>';
            if (item.status) {
                if (item.note || item.additional_image) {
                    message += `<h6 class="mt-4">${app.trans('Additional details')}</h6>`;
                    message += `<ul class="list-group">`;
                    if (item.note) {
                        message += `<li class="list-group-item">
                                <div class="d-flex align-items-start flex-column">
                                    <strong>${app.trans('Note')}</strong>
                                    <span>
                                        ${item.note}
                                    </span>
                                </div>
                            </li>`;
                    }
                    if (item.additional_image) {
                        message += `<li class="list-group-item">
                                <div class="d-flex align-items-start flex-column">
                                    <strong>${app.trans('Screenshot')}</strong>
                                    <img class="w-100 mb-2" src="${item.additional_image}" alt="Image">
                                </div>
                            </li>`;
                    }
                    message += '</ul>';
                } else {
                    message += `<div class="alert alert-info alert-has-icon mt-4 mb-0">
                                    <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                    <div class="alert-body">
                                    <div class="alert-title"></div>
                                        ${app.trans('No extra details are being provided.')}
                                    </div>
                                </div>`;
                }
            }

            var dialog = bootbox.dialog({
                title: app.trans('Withdrawal details'),
                message: message,
                buttons: {
                    cancel: {
                        label: app.trans("Close"),
                        className: 'btn-danger'
                    }
                }
            });
        }
    });
};

app.userWithdrawHistoryDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.userWithdrawHistoryDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.userWithdrawHistoryDatatable.init = function() {
    this.initDatatable();
    this.initShowDetails();
};

$(document).ready(app.userWithdrawHistoryDatatable.init());
