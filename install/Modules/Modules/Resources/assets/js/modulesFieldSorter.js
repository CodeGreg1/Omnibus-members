"use strict";

// define app.modulesFieldSorter object
app.modulesFieldSorter = {};

// handle modifying table column style for drag and drop so that it is not transparent background color
app.modulesFieldSorter.modifyColumnStyles = function() {
    $("#module-fields-datatable table tbody#fields td").each(function () {
        $(this).css({'width': $(this).outerWidth(), 'background':'white'});
    });
};

// handle ajax drag and drop initialization with ajax request upon sorted
app.modulesFieldSorter.init = function() {
    if(typeof $.fn.sortable !== 'undefined') {
        app.modulesFieldSorter.modifyColumnStyles();
        
        $( "#module-fields-datatable tbody#fields" ).sortable({  
            delay: 150,  
            handle: '.drag-handle',
            cursor: 'move',
            axis: 'y',
            placeholder:'placeholder'
        });  
    }

    
};

// initialize app.modulesFieldSorter object until the document is loaded
$(document).ready(app.modulesFieldSorter.init());