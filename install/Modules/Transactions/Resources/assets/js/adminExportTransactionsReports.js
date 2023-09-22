"use strict";

app.adminExportTransactionsReport = {}

app.adminExportTransactionsReport.config = {
    button: {
        print: '.btn-print-transactions',
        excel: '.btn-export-transaction-excel',
        pdf: '.btn-export-transaction-pdf'
    }
};

app.adminExportTransactionsReport.exportExcel = function() {
    const self = this;
    $(self.config.button.excel).click(function() {
        window.location = '/admin/transactions/reports/export-excel?'
            +app.adminTransactionReportsDatatable.table.getQuery();
    });
};

app.adminExportTransactionsReport.exportPdf = function() {
    const self = this;
    $(self.config.button.pdf).click(function() {
        window.location = '/admin/transactions/reports/export-pdf?'
            +app.adminTransactionReportsDatatable.table.getQuery();
    });
};

app.adminExportTransactionsReport.print = function() {
    const self = this;
    $(self.config.button.print).click(function() {
        const route = '/admin/transactions/reports/datatable/print-preview?'
            +app.adminTransactionReportsDatatable.table.getQuery();
        var $button = $(this);
        var $content = $button.html();

        $.ajax({
            type: 'GET',
            url: route,
             beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.backButtonContent($button, $content);
                var frame1 = $('<iframe />');
                frame1[0].name = "frame1";
                frame1.css({ "position": "absolute", "top": "-1000000px" });
                $("body").append(frame1);
                var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
                frameDoc.document.open();
                //Create a new HTML document.
                frameDoc.document.write(response);
                frameDoc.document.close();
                setTimeout(function () {
                    window.frames["frame1"].focus();
                    window.frames["frame1"].print();
                    frame1.remove();
                }, 500);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                app.backButtonContent($button, $content);
                app.notify(response.responseJSON.message);
            }
        });
    });


};

app.adminExportTransactionsReport.init = function() {
    this.exportExcel();
    this.exportPdf();
    this.print();
};

$(document).ready(app.adminExportTransactionsReport.init());
