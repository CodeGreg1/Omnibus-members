"use strict";

// handle on generating template for float field
app.modulesFieldExtraOptions.template.float = function() {
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

        // Float accuracy
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="float_accuracy">'+app.trans('Float accuracy')+'</label>';
                html += '<input type="number" class="form-control" name="float_accuracy" value="2" placeholder="'+app.trans('Float accuracy')+'">';
            html += '</div>';
        html += '</div>';    

        // Float length
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="float_length">'+app.trans('Float length')+'</label>';
                html += '<input type="number" class="form-control" name="float_length" value="10" placeholder="'+app.trans('Float length')+'">';
            html += '</div>';
        html += '</div>';    


    html += '</div>';

    // Default value
    html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="default_value">'+app.trans('Default value')+'</label>';
                html += '<input type="number" class="form-control" name="default_value" step="0.01" placeholder="'+app.trans('Default value')+'">';
            html += '</div>';
        html += '</div>';                       
    html += '</div>';

    return html;
};