"use strict";

/**----------------------------------------------------------------------
 * [FORM RESET]
 * -Resetting form
 * 
 * @param  {string}  selector    - Form selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formReset = function(selector) {

    if($(selector+' .select2').length) {
        $(selector+' .select2').val('').trigger( 'change' );
    }

    $(selector)[0].reset();
};