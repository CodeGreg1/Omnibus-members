"use strict";

// define app.modulesFieldExtraOptions object
app.modulesFieldExtraOptions = {};

// define app.modulesFieldExtraOptions.template object
app.modulesFieldExtraOptions.template = {};

// handle app.modulesFieldExtraOptions.template object on getting value
app.modulesFieldExtraOptions.template.get = function(field) {

	if(app.modulesFieldExtraOptions.template[field] !== undefined) {
		return app.modulesFieldExtraOptions.template[field]();
	}

	return '';
	
};