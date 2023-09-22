"use strict";

// handle on generating template text field
app.modulesFieldExtraOptions.template.text = function() {
	var html = '';
	html += '<h4>'+app.trans('Extra options')+'</h4>';
    html += '<div class="row">';

    	// Min length
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="min_length">'+app.trans('Min length')+'</label>';
                html += '<input type="number" class="form-control" name="min_length" placeholder="'+app.trans('Min length')+'">';
            html += '</div>';
        html += '</div>';

        // Max length
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="max_length">'+app.trans('Max length')+'</label>';
                html += '<input type="number" class="form-control" name="max_length" placeholder="'+app.trans('Max length')+'">';
            html += '</div>';
        html += '</div>';                        
    html += '</div>';

    // Default value
    html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="default_value">'+app.trans('Default value')+'</label>';
                html += '<input type="text" class="form-control" name="default_value" placeholder="'+app.trans('Default value')+'">';
            html += '</div>';
        html += '</div>';                       
    html += '</div>';

    return html;
};