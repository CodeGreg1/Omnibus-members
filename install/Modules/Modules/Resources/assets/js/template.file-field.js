"use strict";

// handle on generating template file field
app.modulesFieldExtraOptions.template.file = function() {
	var html = '';
  html += '<h4>'+app.trans('Extra options')+'</h4>';

    // Default value
    html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<div class="form-group">';
                html += '<label for="max_file_size">'+app.trans('Max file size')+'</label>';
                html += '<div class="input-group mb-2">';
                    html += '<input name="max_file_size" type="text" class="form-control" id="inlineFormInputGroup1" value="2">';
                    html += '<div class="input-group-append">';
                        html += '<div class="input-group-text">'+app.trans('MB')+'</div>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';

        html += '</div>'; 

    html += '</div>';

    
    html += '<div class="row">';

        html += '<div class="col-6 col-md-6">';
                html += '<label class="form-check-label">';
                    html += '<input type="checkbox" name="multiple_files"> <span class="checkbox-label">'+app.trans('Multiple files')+'</span>';
                html += '</label>';                      
        html += '</div>';

    html += '</div>';

    return html;
};