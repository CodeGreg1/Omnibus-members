"use strict";

// define app.modulesFieldChoices.remove object
app.modulesFieldChoices.remove = {};

// handle app.modulesFieldChoices.remove object configuration
app.modulesFieldChoices.remove.config = {
	button: '.btn-remove-field-choice'
};

// handle app.modulesFieldChoices.remove object execution function
app.modulesFieldChoices.remove.execute = function() {
	$(document).delegate(app.modulesFieldChoices.remove.config.button, 'click', function() {
		$(this).parents('td').parents('tr').remove();
	});
};

// handle app.modulesFieldChoices.remove object initialization
app.modulesFieldChoices.remove.init = function() {
    app.modulesFieldChoices.remove.execute();
};

// initialize app.modulesFieldChoices.remove object until the document is loaded
$(document).ready(app.modulesFieldChoices.remove.init());