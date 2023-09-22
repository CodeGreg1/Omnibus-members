"use strict";

// handle on generating template for select field
app.modulesFieldExtraOptions.template.select = function() {
	return app.modulesFieldExtraOptions.template.choices();
};