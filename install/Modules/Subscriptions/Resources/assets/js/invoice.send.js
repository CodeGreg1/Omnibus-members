app.invoiceSend = {};

app.invoiceSend.config = {
    button: '#btn-send-invoice'
};

app.invoiceSend.process = function(e) {
    var route = $(e.target).data('route');
    var dialogSendInvoice = bootbox.dialog({
        message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> '+app.trans('Sending') + '...</p>',
        closeButton: false
    });

    $.ajax({
        type: 'GET',
        url: route,
        success: function (response, textStatus, xhr) {
            setTimeout(function() {
                app.notify(response.message);
                dialogSendInvoice.modal('hide');
            }, 400);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            setTimeout(function() {
                app.notify(response.responseJSON.message);
                dialogSendInvoice.modal('hide');
            }, 400);
        }
    });
};

app.invoiceSend.init = function() {
    $(this.config.button).on('click', this.process.bind());
};

$(document).ready(app.invoiceSend.init());
