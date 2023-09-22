"use strict";

// define app.modulesFieldUpdateValueByKey object
app.modulesFieldUpdateValueByKey = {};

// handle on setting module field update value by key
app.modulesFieldUpdateValueByKey.set = function(data, key, value) {

	if(key == 'field_validation') {
		value = value == 1 ? 'required' : 'optional';
	} else {
		value = value == 1 ? 'on' : 'off';
	}

	var isKeyFound = 0;
	$.each(data, function() {
		var that = this;
		if(this.name == key) {
			isKeyFound = 1;
			this.value = value;
		}
	});

	// append element if key not exists
	if(isKeyFound == 0) {
		data.push({name: key, value: value});
	}

	return data;
};