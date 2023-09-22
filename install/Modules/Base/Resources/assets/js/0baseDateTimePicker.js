"use strict";

/**----------------------------------------------------------------------
 * [Initialize app.baseDateTimePicker object]
 *--------------------------------------------------------------*/
app.baseDateTimePicker = {};

/**----------------------------------------------------------------------
 * [Handle initialization of date and time picker]
 *--------------------------------------------------------------*/
app.baseDateTimePicker.init = function() {
    if(jQuery().daterangepicker) {

        if($(".datepicker").length) {
            $('.datepicker').daterangepicker({
                locale: {format: 'YYYY-MM-DD'},
                singleDatePicker: true,
            });
        }

        if($(".datetimepicker").length) {
            $('.datetimepicker').daterangepicker({
                locale: {format: 'YYYY-MM-DD HH:mm'},
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            });
        }

    }

    if(jQuery().timepicker && $(".timepicker").length) {
        $(".timepicker").timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            icons: {
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down'
            }
        });
    }
};

/**----------------------------------------------------------------------
 * [initialize app.baseDateTimePicker object until the document is loaded]
 *--------------------------------------------------------------*/
$(document).ready(app.baseDateTimePicker.init());
