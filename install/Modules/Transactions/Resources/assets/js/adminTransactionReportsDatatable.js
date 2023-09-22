"use strict";

app.adminTransactionReportsDatatable = {};

app.adminTransactionReportsDatatable.config = {
    dateFormat: 'YYYY-MM-DD H:m:s',
    rangePicker: null,
    datatable: '#admin-transactions-report-datatable',
    options: {
        src: '/admin/transactions/reports/datatable',
        resourceName: { singular: app.trans("transaction"), plural: app.trans("transactions") },
        columns: [
            {
                title: app.trans('Trx'),
                key: 'trx',
                classes: 'transaction-trx-row'
            },
            {
                title: app.trans('Date'),
                key: 'date',
                classes: 'transaction-date-row',
                searchable: false
            },
            {
                title: app.trans('User'),
                key: 'user',
                classes: 'transaction-user-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<div class="d-flex flex-column">
                        <span>${row.user.full_name}</span>
                        <span>${row.user.email}</span>
                    </div>`);
                }
            },
            {
                title: app.trans('Type'),
                key: 'type',
                classes: 'transaction-type-row',
                orderable: false,
                searchable: false
            },
            {
                title: app.trans('Amount'),
                key: 'total',
                classes: 'transaction-total-row',
                searchable: false,
                orderable: false,
                element: function(row) {
                    return $(`<span>${row.total}</span>`);
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
                    return $(`<span>${row.grand_total}</span>`);
                }
            },
            {
                title: app.trans('Actions'),
                key: 'actions',
                searchable: false,
                classes: 'transaction-actions-column tb-actions-column text-right',
                element: function(row) {
                    return $(`<a href="javascript:void(0)" class="btn btn-primary btn-sm btn-admin-transaction-report-details-show" data-id="${row.id}"><i class="fas fa-eye"></i></a>`);
                }
            },
            {
                title: app.trans('First Name'),
                key: 'user.first_name',
                searchable: true,
                hidden: true,
            },
            {
                title: app.trans('Last Name'),
                key: 'user.last_name',
                searchable: true,
                hidden: true,
            },
            {
                title: app.trans('Email'),
                key: 'user.email',
                searchable: true,
                hidden: true,
            },
            {
                title: 'amount',
                key: 'amount',
                hidden: true,
            },
            {
                title: 'fixed_charge',
                key: 'fixed_charge',
                hidden: true,
            },
            {
                title: 'percent_charge',
                key: 'percent_charge',
                hidden: true,
            },
            {
                title: 'charge',
                key: 'charge',
                hidden: true,
            },
            {
                title: 'description',
                key: 'description',
                hidden: true,
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No transactions were found!")
        },
        selectable: false,
        limit: 25,
        filterControl: [
            {
                key: 'type',
                title: app.trans('Type'),
                choices: [
                    { label: app.trans('Deposit'), value: 'Deposit' },
                    { label: app.trans('Withdrawal'), value: 'Withdrawal' },
                    { label: app.trans('Transfer'), value: 'Transfer' },
                    { label: app.trans('Exchange'), value: 'Exchange' },
                    { label: app.trans('Subscription'), value: 'Subscription' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: true,
                value: ''
            }
        ],
        load: false,
        onRowClick: function(row) {
            console.log(row)
        }
    }
};

app.adminTransactionReportsDatatable.initShowDetails = function() {
    const self = this;
    $(document).delegate('.btn-admin-transaction-report-details-show', 'click', function() {
        var id = $(this).closest('a').data('id');
        const item = app.adminTransactionReportsDatatable.table.findItem('id', parseInt(id));
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
                                <span class="mr-4">${app.trans('Type')}</span>
                                <span>
                                    ${item.type}
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

app.adminTransactionReportsDatatable.initDatatable = function() {
    if(typeof $.fn.JsDataTable !== 'undefined' && $(this.config.datatable).length) {
        app.adminTransactionReportsDatatable.table = $(this.config.datatable).JsDataTable(this.config.options);
    }
};

app.adminTransactionReportsDatatable.setRangeLabel = function(start, end) {
    var html = '';
    if (start.format('MMM D, YYYY') === end.format('MMM D, YYYY')) {
        html = start.format('MMM D, YYYY');
    } else if (start.format('YYYY') === end.format('YYYY')) {
        html = start.format('MMM D') + ' - ' + end.format('MMM D, YYYY');
    } else {
        html = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
    }
    $('.btn-admin-transactions-report-daterangepicker span').html(html);
};

app.adminTransactionReportsDatatable.init = function() {
    const self = this;
    this.initDatatable();
    this.initShowDetails();

    if(jQuery().daterangepicker && $('.btn-admin-transactions-report-daterangepicker').length) {
        const startDate = moment().subtract(29, 'days');
        const endDate = moment();

        app.adminTransactionReportsDatatable.config.rangePicker = $('.btn-admin-transactions-report-daterangepicker').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: startDate,
            endDate: endDate
        }, function(start, end) {
            app.adminTransactionReportsDatatable.setRangeLabel(start, end);
            app.adminTransactionReportsDatatable.table.setQuery({
                date: [
                    start.startOf('day').format(self.config.dateFormat),
                    end.endOf('day').format(self.config.dateFormat)
                ].join(",")
            }, true);
        });

        app.adminTransactionReportsDatatable.setRangeLabel(startDate, endDate);
        app.adminTransactionReportsDatatable.table.setQuery({
            date: [
                startDate.format(self.config.dateFormat),
                endDate.format(self.config.dateFormat)
            ].join(",")
        }, true);
    }
};

$(document).ready(app.adminTransactionReportsDatatable.init());
