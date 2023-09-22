"use strict";

// define app.adminDashboardWidgetsUpdate object
app.adminDashboardWidgetsUpdate = {};

// handle initializing select2 and click the type element checkbox if checked
app.adminDashboardWidgetsUpdate.init = function() {
    if(typeof $.fn.select2 !== 'undefined') {
        setTimeout(function() {
            $('.field-to-show-relationship-fields').select2({
                placeholder: app.trans('Select Relationship Fields')
            });
        }, 2000);
    }

    $('[name="type"]:checked').trigger('click');
};

// initialize app.adminDashboardWidgetsUpdate object until the document is loaded
$(document).ready(app.adminDashboardWidgetsUpdate.init());