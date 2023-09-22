"use strict";

// define app.adminSettingsRemoveFile object
app.adminSettingsRemoveFile = {};

// handle on removing file element
app.adminSettingsRemoveFile.remove = function() 
{
	$(document).delegate('.settings-remove-dropzone-file', 'click', function() {
		var that = $(this);
        var dzId = that.attr('data-dz-id');

		var dz = $(dzId)[0].dropzone;

		that.parents('.list-group-item').remove();
		
		dz.options.maxFiles = dz.options.maxFiles + 1; 
	});
};

// initialize functions of app.adminSettingsRemoveFile object
app.adminSettingsRemoveFile.init = function() 
{
	app.adminSettingsRemoveFile.remove();
};

// initialize app.adminSettingsRemoveFile object until the document is loaded
$(document).ready(app.adminSettingsRemoveFile.init());