"use strict";

// define app.modulesUserCrud object
app.modulesUserCrud = {};

// handle on enable/disable only subscribers checkbox
app.modulesUserCrud.checkbox = function(e) {
	$('[name="included[user]"]').on('click', function() {
		if($(this).is(':checked')) {
			$('[name="included[only_subscribers]"]').prop('disabled', false);
		} else {
			$('[name="included[only_subscribers]"]').prop('disabled', true);
		}
	});
};

// initialize user crud functionality
app.modulesUserCrud.init = function() {
    app.modulesUserCrud.checkbox();
};

// initialize app.modulesUserCrud object until the document is loaded
$(document).ready(app.modulesUserCrud.init());