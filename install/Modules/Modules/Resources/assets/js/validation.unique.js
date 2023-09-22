"use strict";

// handle app.modulesValidation.validation object on generating unique field template  
app.modulesValidation.validation.unique = function() {
	var html = '';
    
	html += '<option value="optional">'+app.trans('Optional')+'</option>';
    html += '<option value="required">'+app.trans('Required')+'</option>';
    html += '<option value="required_unique">'+app.trans('Required | Unique')+'</option>';

    return html;
};