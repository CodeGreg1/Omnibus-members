"use strict";

// define app.modulesFieldChoices object
app.modulesFieldChoices = {};

// define app.modulesFieldChoices.addNew object
app.modulesFieldChoices.addNew = {};

// handle app.modulesFieldChoices.addNew object configuration
app.modulesFieldChoices.addNew.config = {
	table: '.table-field-choices'
};

// handle on generating template for modules field choices
app.modulesFieldChoices.addNew.template = function(counter) {
	var html = '<tr>';
      	html += '<td><input type="text" class="form-control" name="choices_database_value['+counter+']" required data-validation="required|'+app.trans('The database value field is required.')+'"></td>';
      	html += '<td><input type="text" class="form-control" name="choices_label_text['+counter+']" required data-validation="required|'+app.trans('The label text field is required.')+'"></td>';
      	html += '<td><button type="button" class="btn btn-danger btn-sm btn-remove-field-choice"><i class="fas fa-trash"></i></td>';
    html += '</tr>';

    return html;
};

// handle on add button event modules field choices
app.modulesFieldChoices.addNew.add = function() {

	$(document).delegate('.btn-add-new-key-value', 'click', function() {

		var count = $(app.modulesFieldChoices.addNew.config.table + '> tbody > tr').length;

		$(app.modulesFieldChoices.addNew.config.table + ' tbody').append(app.modulesFieldChoices.addNew.template(count));
	});

};

// initialize app.modulesFieldChoices.addNew add function
app.modulesFieldChoices.addNew.init = function() {
    app.modulesFieldChoices.addNew.add();
};

// initialize app.modulesFieldChoices.addNew object until the document is loaded
$(document).ready(app.modulesFieldChoices.addNew.init());