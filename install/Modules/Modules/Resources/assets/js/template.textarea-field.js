"use strict";

// handle on generating template for textarea
app.modulesFieldExtraOptions.template.textarea = function() {
	var html = '';
	html += '<h4>Extra options</h4>';
    html += '<div class="row">';
        html += '<div class="col-3 col-md-3">';
            html += '<label class="form-check-label">';
                html += '<input type="checkbox" name="field_textarea_tinymce"> <span class="checkbox-label">'+app.trans('Use TinyMCE')+'</span>';
            html += '</label>';                      
    html += '</div>';

    return html;
};