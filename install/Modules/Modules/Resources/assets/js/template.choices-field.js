"use strict";

// handle on generating template for choices field
app.modulesFieldExtraOptions.template.choices = function() {
	var html = '';
	html += '<h4>Extra options</h4>';

    html += '<table class="table table-field-choices">';
        html += '<thead class="thead-dark">';
            html += '<tr>';
              	html += '<th scope="col" width="45%">'+app.trans('Database Value')+'</th>';
              	html += '<th scope="col" width="45%">'+app.trans('Label text')+'</th>';
              	html += '<th scope="col" width="10%">&nbsp;</th>';
            html += '</tr>';
        html += '</thead>';
        html += '<tbody>';
            html += '<tr>';
              	html += '<td><input type="text" class="form-control" name="choices_database_value[0]" required data-validation="required|'+app.trans('The database value field is required.')+'"></td>';
              	html += '<td><input type="text" class="form-control" name="choices_label_text[0]" required data-validation="required|'+app.trans('The label text field is required.')+'"></td>';
              	html += '<td><button type="button" class="btn btn-danger btn-sm btn-remove-field-choice" disabled><i class="fas fa-trash"></i></td>';
            html += '</tr>';
        html += '</tbody>';
    html += '</table>';

    html += '<div class="text-right"><button type="button" class="btn btn-info btn-add-new-key-value"><i class="fas fa-plus"></i> '+app.trans('Add new key/value set')+'</button></div>';

    return html;
};