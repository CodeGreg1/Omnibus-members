"use strict";

// define app.adminFrontend object
app.adminFrontend = {};

app.adminFrontend.colorSchemeType = function() {
	$('#admin-frontend-settings-form [name="frontend_color_scheme"]').on('change', function() {
		var value = $(this).val();

		if(value == 'dark') {
			$('.dark-color-wrapper').show();
		} else {
			$('.dark-color-wrapper').hide();
		}
	});
};

app.adminFrontend.sectionFrontendType = function() {
	$('#admin-frontend-create-form [name="type"]').on('change', function() {
		var value = $(this).val();

		if(value == 'section') {
			$('.frontend-section-wrapper').show();
		}

		if(value == 'element') {
			$('.frontend-section-wrapper').hide();
		}
	});
};

app.adminFrontend.sectionFrontendSectionData = function() {
	$('#admin-frontend-create-form [name="section_data_source"]').on('change', function() {
		var value = $(this).val();

		if(value == 'manual') {
			$('.frontend-section-data-wrapper').show();
		} else {
			$('.frontend-section-data-wrapper').hide();
		}
	});
};

app.adminFrontend.sectionBackgroundType = function() {
	$("[name='section_background_type']").on('change', function() {
		var value = $(this).val();

		if(value == 'colored') {
			$('.section-background-color-wrapper').show();
			$('.section-background-image-wrapper').hide();

			Dropzone.forElement('#frontend-section_background_image-dropzone').removeAllFiles(true);
		}

		if(value == 'imaged') {
			$('.section-background-image-wrapper').show();
			$('.section-background-color-wrapper').hide();

			$('.section-background-color-wrapper').find('[name="section_background_color"]').val('');
			$('.section-background-color-wrapper .input-group-append').find('i').removeAttr('style');

		}
	});
};

// initialize functions of app.adminFrontend object
app.adminFrontend.init = function() {
    if(typeof $.fn.colorpicker !== 'undefined') {
    	$(".section-color-picker").colorpicker({
	      	format: 'hex',
	      	component: '.input-group-append',
	    });
    }

    app.adminFrontend.colorSchemeType();
    app.adminFrontend.sectionFrontendType();
    app.adminFrontend.sectionBackgroundType();
    app.adminFrontend.sectionFrontendSectionData();
};

// initialize app.adminFrontend object until the document is loaded
$(document).ready(app.adminFrontend.init());