"use strict";

// handle on generating template for radio field
app.modulesFieldExtraOptions.template.radio = function() {
	return app.modulesFieldExtraOptions.template.choices();
};