"use strict";

// define app.adminCategories object
app.adminCategories = {};


// initialize functions of app.adminCategories object
app.adminCategories.init = function() {
    if(typeof $.fn.colorpicker !== 'undefined') {
    	$(".colorpickerinput").colorpicker({
	      	format: 'hex',
	      	component: '.input-group-append',
	    });
    }
};

// initialize app.adminCategories object until the document is loaded
$(document).ready(app.adminCategories.init());