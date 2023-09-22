"use strict";

/**----------------------------------------------------------------------
 * [Initialize app.baseDropzone object]
 *--------------------------------------------------------------*/
app.baseDropzone = {};

/**----------------------------------------------------------------------
 * [Handle app.baseDropzone object configuration]
 *--------------------------------------------------------------*/
app.baseDropzone.config = {
	defaultThumbnail: '/upload/media/default/file-thumb.png'
};

/**----------------------------------------------------------------------
 * [Handle app.baseDropzone object remove file]
 *--------------------------------------------------------------*/
app.baseDropzone.remove = function() 
{
	$(document).delegate('.remove-dropzone-file', 'click', function() {

		var that = $(this);
        var dzId = that.attr('data-dz-id');
        var route = that.attr('data-route');

		$.ajax({
            type: 'DELETE',
            url: route,
            data: {uid:that.attr('data-id')},
            success: function (response, textStatus, xhr) {
            	var dz = $(dzId)[0].dropzone;

				that.parents('.list-group-item').remove();
				
				dz.options.maxFiles = dz.options.maxFiles + 1;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {

            }
        }); 
	});
};

/**----------------------------------------------------------------------
 * [Handle app.baseDropzone object remove errors]
 *--------------------------------------------------------------*/
app.baseDropzone.removeErrors = function(e) 
{
	// remove errors
    $(e.previewElement).removeClass('error');
    $(e.previewElement).parents('.form-group').removeClass('error');
    $(e.previewElement).parents('.form-group').find('.message').remove();
    $(e.previewElement).parents('.form-group').find('.dz-preview').find('.list-group-item').removeClass('error');
};

/**----------------------------------------------------------------------
 * [Handle app.baseDropzone object count uploaded files]
 *--------------------------------------------------------------*/
app.baseDropzone.countUploaded = function(cls) 
{
	return $(cls + ' li.list-group-item').length;
};

/**----------------------------------------------------------------------
 * [call all functions to initialize for app.baseDropzone object]
 *--------------------------------------------------------------*/
app.baseDropzone.init = function(cls) 
{
	app.baseDropzone.remove();
};

/**----------------------------------------------------------------------
 * [initialize app.baseDropzone object until the document is loaded]
 *--------------------------------------------------------------*/
$(document).ready(app.baseDropzone.init());