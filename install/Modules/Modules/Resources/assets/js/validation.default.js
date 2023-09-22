"use strict";

// define app.modulesValidation object
app.modulesValidation = {};

// define app.modulesValidation.validation object
app.modulesValidation.validation = {};

// handle app.modulesValidation.validation object configuration
app.modulesValidation.validation.config = {
    uniqueValidationFields: [
        'text',
        'email',
        'number',
        'float',
        'money'
    ]
};

// handle app.modulesValidation.validation object rules 
app.modulesValidation.validation.rules = function(type) {
    var requiredUnique = app.modulesValidation.validation.unique();

    var validationFields = app.modulesValidation.validation.config.uniqueValidationFields;

    if(validationFields.indexOf(type) !== -1) {
        return app.modulesValidation.validation.unique();
    }

    return app.modulesValidation.validation.default();
};

// handle app.modulesValidation.validation object default template 
app.modulesValidation.validation.default = function() {
	var html = '';

	html += '<option value="optional">'+app.trans('Optional')+'</option>';
    html += '<option value="required">'+app.trans('Required')+'</option>';

    return html;
};