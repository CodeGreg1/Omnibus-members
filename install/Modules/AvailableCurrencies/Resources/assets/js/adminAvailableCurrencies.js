"use strict";

// define adminAvailableCurrencies object
app.adminAvailableCurrencies = {};

// handle ajax request getting currency details as well as the currency code
app.adminAvailableCurrencies.code = function() {
	$('[name="currency_id"]').on('change', function() {
		var $that = $(this);
		var $id = $that.val();

		$.ajax({
            type: 'GET',
            url: '/admin/currencies/' + $id + '/get-currency/',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
            	$('form [name="name"]').val('');
                $('form [name="symbol"]').val('');
                $('form [name="code"]').val('');
                $('form [name="format"]').val('');
            },
            success: function (response, textStatus, xhr) {
                $('form [name="name"]').val(response.data.name);
                $('form [name="symbol"]').val(response.data.symbol);
                $('form [name="code"]').val(response.data.code);
                $('form [name="format"]').val(response.data.format);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                app.notify(response.responseJSON.message);
            }
        }); 
	});
};

// call all available functions of adminAvailableCurrencies object
app.adminAvailableCurrencies.init = function() {
    app.adminAvailableCurrencies.code();
};

// initialize adminAvailableCurrencies object until the document is loaded
$(document).ready(app.adminAvailableCurrencies.init());