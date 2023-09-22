"use strict";

// define app.adminDashboardWidgetsSort object
app.adminDashboardWidgetsSort = {};

// handle ajax drag and drop initialization with ajax request upon sorted
app.adminDashboardWidgetsSort.init = function() {
    if(typeof $.fn.sortable !== 'undefined') {
        $("#admin-dashboardwidgets-datatable table td").each(function () {
            $(this).css("width", $(this).outerWidth());
        });
        
        $( "#admin-dashboardwidgets-datatable tbody" ).sortable({  
            delay: 150,  
            handle: '.drag-handle',
            cursor: 'move',
            placeholder:'placeholder',
            stop: function() {  
                var dataOrdering = new Array();  
                $('#admin-dashboardwidgets-datatable tbody>tr').each(function() {  
                    var row = $(this);
                    var handleCol = row.find('.dashboardwidgets-handle-column');
                    var id = handleCol.find('>span').attr('data-id');
                    var ordering = handleCol.find('>span').attr('data-ordering');
                    
                    dataOrdering.push({
                        id: id,
                        ordering: ordering
                    });
                });

                $.ajax({
                    type: 'POST',
                    url:"/admin/dashboard-widgets/reorder",  
                    dataType: "json",
                    data: {ordering: dataOrdering},
                    success: function (response, textStatus, xhr) {
                        app.notify(response.message);
                    }, 
                })   
            }
        });  
    }

    
};

// initialize app.adminDashboardWidgetsSort object until the document is loaded
$(document).ready(app.adminDashboardWidgetsSort.init());