"use strict";

// define app.adminSettings object
app.adminSettings = {};

// handle app.adminSettings manipulating mail driver element event
app.adminSettings.mail = function() {
	$('[name="mail_driver"]').on('change', function() {
		var value = $(this).val();

		$('[name="mail_driver"] > option').each(function() {
			if(value != this.value) {
				$('.' + this.value + '-wrapper').removeClass('display-flex');
				$('.' + this.value + '-wrapper').addClass('display-none');
			}
		});
		
		$('.' + value + '-wrapper').removeClass('display-none');
		$('.' + value + '-wrapper').addClass('display-flex');
	});
};

// initialize functions of app.adminSettings object
app.adminSettings.init = function() {
    app.adminSettings.mail();
};

// initialize app.adminSettings object until the document is loaded
$(document).ready(app.adminSettings.init());