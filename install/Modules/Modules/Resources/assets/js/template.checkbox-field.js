"use strict";

// handle on generating template extra field checkbox
app.modulesFieldExtraOptions.template.checkbox = function() {
	var html = '';
    html += '<h4>'+app.trans('Extra options')+'</h4>';
	html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="default_value">'+app.trans('Default value')+'</label>';
                html += '<select name="default_value" class="form-control custom-select">';
                    html += '<option value="unchecked">'+app.trans('Unchecked')+'</option>';
                    html += '<option value="checked">'+app.trans('Checked')+'</option>';
                html += '</select>';
            html += '</div>';
        html += '</div>';
    html += '</div>';

    return html;
};