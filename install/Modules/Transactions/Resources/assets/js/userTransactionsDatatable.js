"use strict";

app.userTransactionsDatatable = {};

app.userTransactionsDatatable.config = {
    datatable: '#user-transactions-datatable',
    options: {
        src: '/user/transactions/datatable',
        resourceName: { singular: app.trans("transaction"), plural: app.trans("transactions") },
        columns: [
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'transaction-date-row'
            },
            {
                title: app.trans('Description'),
                key: 'description',
                classes: 'transaction-description-row'
            },
            {
                title: app.trans('Initial Balance'),
                key: 'initial_balance',
                classes: 'transaction-initial_balance-row'
            },
            {
                title: app.trans('Amount'),
                key: 'total',
                classes: 'transaction-total-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<strong>${row.total}</strong>`);
                }
            },
            {
                title: app.trans('Charge'),
                key: 'total_charge',
                classes: 'transaction-charge-row',
                orderable: false,
                searchable: false
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
                    return $(`<a href="javascript:void(0)" class="btn btn-primary btn-sm btn-user-transaction-request-details-show" data-id="${row.id}"><i class="fas fa-eye"></i></a>`);
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

app.userTransactionsDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-user-transaction-request-details-show', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.userTransactionsDatatable.tb.findItem('id', parseInt(id));
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

app.userTransactionsDatatable.init = function() {
    app.userTransactionsDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.userTransactionsDatatable.tb = $(this.config.datatable).JsDataTable(this.config.options);
    }

    app.userTransactionsDatatable.initShowDetails();
};

$(document).ready(app.userTransactionsDatatable.init());
