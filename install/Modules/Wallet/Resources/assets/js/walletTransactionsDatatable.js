"use strict";

app.walletTransactionsDatatable = {};

app.walletTransactionsDatatable.config = {
    datatable: '#wallet-transactions-datatable',
    options: {
        src: '/profile/wallet/transactions/datatable',
        resourceName: { singular: app.trans("transaction"), plural: app.trans("transactions") },
        columns: [
            {
                title: app.trans('Description'),
                key: 'description',
                classes: 'transaction-description-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<div class="d-flex flex-column">
                        <span>${row.description}</span>
                        <span class="text-muted">${row.date}</span>
                    </div>`);
                }
            },
            {
                title: app.trans('Initial Balance'),
                key: 'initial_balance',
                classes: 'transaction-initial_balance-row',
                searchable: false,
                orderable: false,
            },
            {
                title: app.trans('Amount'),
                key: 'total',
                classes: 'transaction-total-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    var color = 'text-dark';
                    if (row.prefix === '+') {
                        color = 'text-success';
                    }
                    if (row.prefix === '-') {
                        color = 'text-danger';
                    }

                    return $(`<div class="d-flex flex-column">
                        <strong class="${color}">${row.total}</strong>
                        <span class="text-muted">${row.total_charge}</span>
                    </div>`);
                }
            },
            {
                title: app.trans('Total'),
                key: 'grand_total',
                classes: 'transaction-grand-total-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<strong>${row.grand_total}</strong>`);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'transaction-actions-column tb-actions-column text-right',
                element: function(row) {
                    return $(`<a href="javascript:void(0)" class="btn btn-primary btn-sm btn-transaction-request-details-show" data-id="${row.id}"><i class="fas fa-eye"></i></a>`);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No transactions were found!")
        },
        selectable: false,
        showSearchQuery: false,
        limit: 25,
        onRowClick: function(row) {
            console.log(row)
        }
    }
};

app.walletTransactionsDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-transaction-request-details-show', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.walletTransactionsDatatable.tb.findItem('id', parseInt(id));
        if (item) {
            var message = `<ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Transaction ID')}</span>
                                <span>
                                    ${item.trx}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Date')}</span>
                                <span>
                                    ${item.date}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Description')}</span>
                                <span>
                                    ${item.description}
                                </span>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Amount')}</span>
                                <span>
                                    ${item.total}
                                </span>
                            </div>
                        </li>`;

            for (let index = 0; index < item.details.length; index++) {
                const detail = item.details[index];
                message += `<li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${detail.label}</span>
                                <span>
                                    ${detail.value}
                                </span>
                            </div>
                        </li>`;
            }

            message += '</ul>'

            var dialog = bootbox.dialog({
                title: app.trans('Transaction details'),
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

app.walletTransactionsDatatable.init = function() {
    app.walletTransactionsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.walletTransactionsDatatable.tb = $(this.config.datatable).JsDataTable(this.config.options);
    }

    app.walletTransactionsDatatable.initShowDetails();
};

$(document).ready(app.walletTransactionsDatatable.init());
