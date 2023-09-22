"use strict";

// define app.modulesOrderByGenerator object
app.modulesOrderByGenerator = {};

// handle on generating template for module order by field option
app.modulesOrderByGenerator.template = function(data) {

	data = JSON.parse(data);

	var fieldName = app.modulesHelper.getData(data, 'field_database_column');
	var fieldDisplay = app.modulesHelper.getData(data, 'field_visual_title');

	var html = '';

	html += '<option value="'+fieldName+'">'+fieldDisplay+'</option>';

    return html;
};