"use strict";

// define app.adminDashboardWidgetsFormatType object
app.adminDashboardWidgetsFormatType = {};

// handle generating template value format type
app.adminDashboardWidgetsFormatType.templateValue = function(row, type) 
{
	var defValue = row.find('.field-to-show-display-format').attr('data-default-value');
	var dbColumn = row.find('td:first-child + td').html();
	var dateTimeTypes = [
		'timeago',
		'date',
		'datetime'
	];

	if(type == 'html') {
		if(defValue != '') {
			return defValue;
		}

		return '$' + dbColumn + '$';
	}

	return type;
};

// call all available functions of app.adminDashboardWidgetsFormatType object to initialize
app.adminDashboardWidgetsFormatType.init = function() {
    $(document).delegate('.field-to-show-format-type', 'change', function(e) {
    	var formatType = $(this).val();
    	var row = $(this).parents('.row-field-to-show');
    	var value = app.adminDashboardWidgetsFormatType.templateValue(row, formatType);

    	if(formatType == 'html') {
    		row.find('.field-to-show-display-format').prop('disabled', false);
    	} else {
    		row.find('.field-to-show-display-format').prop('disabled', true);
    	}

    	row.find('.field-to-show-display-format').val(value);
    });
};

// initialize app.adminDashboardWidgetsFormatType object until the document is loaded
$(document).ready(app.adminDashboardWidgetsFormatType.init());