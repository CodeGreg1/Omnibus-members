"use strict";

// handle on generating belongsToManyRelationship option template
app.modulesFieldExtraOptions.template.belongsToManyRelationship = function() {

    app.modulesFieldExtraOptions.template.belongsToRelationship.model();
    
    var models = $('[name=\"models\"]').val();
	var html = '';
    html += '<h4>'+app.trans('Extra options')+'</h4>';
    html += '<div class="row">';

        // Select table
        html += '<div class="col-4 col-md-4">';
            html += '<div class="form-group">';
                html += '<label for="model">'+app.trans('Model')+'</label>';
                html += '<select class="form-control" name="related_model" required data-validation="required|'+app.trans('The model field is required.')+'">';
                html += '<option value="">'+app.trans('Select model')+'</option>';
                $.each(JSON.parse(models), function(key, data) {
                    $.each(data, function(table, model) {
                        html += '<option value="'+model.namespace+'">'+model.model_name+'</option>';
                    });
                });
                html += '</select>';
            html += '</div>';
        html += '</div>';

        // Select field to show
        html += '<div class="col-4 col-md-4 belongs-to-options-wrapper">';
            html += '<div class="form-group">';
                html += '<label for="field_show_in_list">'+app.trans('Field to show in list')+'</label>';
                html += '<select class="form-control" name="field_show" required data-validation="required|'+app.trans('The field to show in is required.')+'">';

                html += '</select>';
            html += '</div>';
        html += '</div>'; 


    html += '</div>';

    return html;
};