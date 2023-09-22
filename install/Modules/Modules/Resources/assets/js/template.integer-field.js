"use strict";

// handle on generating template for number field
app.modulesFieldExtraOptions.template.number = function() {
	var html = '';
	html += '<h4>'+app.trans('Extra options')+'</h4>';

    // Default value
    html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="default_value">'+app.trans('Default value')+'</label>';
                html += '<input type="number" class="form-control" step="1" name="default_value">';
            html += '</div>';
        html += '</div>';                       
    html += '</div>';

    return html;
};