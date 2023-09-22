"use strict";

// define the app.adminDashboard object
app.adminDashboard = {};

// call the available functions to initialize of the app.adminDashboard object
app.adminDashboard.init = function() {

	$.each($('.chart'), function() {
		var attributes = JSON.parse($(this).attr('data-attributes'));

		app.chart(attributes);
	});

	if($('#cron-not-running-modal').length) {
		$('#cron-not-running-modal').modal('show');
	}

};

// initialize app.adminDashboard object until the document is loaded
$(document).ready(app.adminDashboard.init());