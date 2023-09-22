"use strict";

// define app.modulesHelper object
app.modulesHelper = {};

// handle on getting data
app.modulesHelper.getData = function(data, field) {
	var result = data.map(function(item) {
		return item.name == field ? item.value : '';
	});

	var value;

	$.each(result, function(k, v) {
		if(v != '') {
			value = v;
		}
	});

	return value;
};