"use strict";

app.billingReports = {};

app.billingReports.config = {
    dateFormat: 'YYYY-MM-DD H:m:s',
    rangePicker: null,
    routeOverview: '/admin/reports/billing/overview',
    charts: {
        active_subscribers: {
            chart: null,
            cardId: '#active-subscribers-report'
        },
        new_subscribers: {
            chart: null,
            cardId: '#new-subscribers-report'
        },
        new_trials: {
            chart: null,
            cardId: '#new-trials-report'
        }
    }
};

app.billingReports.chart = function(element) {
    return new Chart(element.getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        borderWidth: 5,
        backgroundColor: 'transparent',
        options: {
            legend: {
                display: false
            },
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false,
                    },
                    ticks: {
                        stepSize: 150,
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        color: '#fbfbfb',
                        lineWidth: 2
                    },
                    ticks: {
                        autoSkip: false,
                        callback: function(val, index) {
                            // Hide every 2nd tick label
                            return index === 0 || index === (this.ticks.length - 1) ? val: '';
                        },
                        maxRotation: 0,
                        minRotation: 0
                    }
                }]
            },
        }
    });
};

app.billingReports.ajax = function(startDate, endDate) {
    const self = this;
    const format = 'YYYY-MM-DD';

    $.ajax({
        type: 'GET',
        data: {
            start: startDate.format(format) + ' 00:00:00',
            end: endDate.format(format) + ' 23:59:59'
        },
        url: self.config.routeOverview,
        success: function (response, textStatus, xhr) {
            Object.keys(response).forEach(function(className, key) {
                const el = $('.billing-report-card .'+className);
                if (el.length) {
                    el.html(response[className]);
                }
            });
            // Object.keys(response).forEach(function(key, index) {
            //     var reportObject = self.config.charts[key];
            //     if (reportObject) {
            //         const billingData = response[key];
            //         if (!reportObject.chart) {
            //             reportObject.chart = self.chart($(reportObject.cardId + ' .report-chart')[0]);
            //         }
            //         var label = [];
            //         var data = [];
            //         for (const [key, value] of Object.entries(billingData.data)) {
            //             label.push(key);
            //             data.push(value);
            //         }

            //         // Clear data
            //         reportObject.chart.data.labels.pop();
            //         reportObject.chart.data.datasets.forEach((dataset) => {
            //             dataset.data.pop();
            //         });
            //         reportObject.chart.update();

            //         reportObject.chart.data.labels = label;
            //         reportObject.chart.data.datasets = [
            //             {
            //                 label: 'Active subscriptions',
            //                 data: data,
            //                 borderWidth: 2,
            //                 borderColor: '#007bff',
            //                 backgroundColor: '#ffffff',
            //                 pointBackgroundColor: '#007bff',
            //                 pointRadius: 1,
            //                 pointHoverRadius: 3
            //             }
            //         ];
            //         reportObject.chart.update();
            //         $(reportObject.cardId + ' .total').html(billingData.total);
            //     }
            // });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            app.notify(response.responseJSON.message);
        }
    });
};

app.billingReports.setRangeLabel = function(start, end) {
    var html = '';
    if (start.format('MMM D, YYYY') === end.format('MMM D, YYYY')) {
        html = start.format('MMM D, YYYY');
    } else if (start.format('YYYY') === end.format('YYYY')) {
        html = start.format('MMM D') + ' - ' + end.format('MMM D, YYYY');
    } else {
        html = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
    }
    $('.daterange-btn span').html(html);
};

app.billingReports.init = function() {
    const self = this;
    if(jQuery().daterangepicker && $('.daterange-btn').length) {
        const startDate = moment().subtract(29, 'days');
        const endDate = moment();

        app.billingReports.config.rangePicker = $('.daterange-btn').daterangepicker({
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
            app.billingReports.setRangeLabel(start, end);
            app.billingReports.ajax(start.startOf('day'), end.endOf('day'));
            app.adminReportBillingDatatable.datatable.setQuery({
                date: [
                    start.startOf('day').format(self.config.dateFormat),
                    end.endOf('day').format(self.config.dateFormat)
                ].join(",")
            }, true);
        });

        app.billingReports.setRangeLabel(startDate, endDate);
        app.billingReports.ajax(startDate.startOf('day'), endDate.endOf('day'));
        app.adminReportBillingDatatable.datatable.setQuery({
            date: [
                startDate.format(self.config.dateFormat),
                endDate.format(self.config.dateFormat)
            ].join(",")
        }, true);
    }
};

$(document).ready(app.billingReports.init());
