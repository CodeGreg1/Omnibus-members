"use strict";

app.formMessageHelper = {};

app.formMessageHelper.config = {
    formErrorMessageClass: '.form-alert-message'
};

/**----------------------------------------------------------------------
 * [REMOVE FORM ERROR MESSAGE]
 * -Remove form error message before showing the another error
 * 
 * @param {string} selector - Form selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formMessageHelper.removeFormMessage = function(selector) {
    if($(selector).find(app.formMessageHelper.config.formErrorMessageClass)) {
        $(selector).find(app.formMessageHelper.config.formErrorMessageClass).remove();
    }
};

/**----------------------------------------------------------------------
 * [SHOW FORM ERROR MESSAGE]
 * -Show form message
 * 
 * @param {string} selector - Form selector
 * @param {string} message - Message
 * @return {any}
 *--------------------------------------------------------------*/
app.formMessageHelper.showFormMessage = function(selector, message, statusCode) {
    var alertType = "success";
    var successStatusCode = [200, 201];

    if(!successStatusCode.includes(statusCode)) {
        alertType = "warning";
    }

    var html = '<div class="alert '+app.removeClassDot(app.formMessageHelper.config.formErrorMessageClass)+' alert-'+alertType+'">';
    html += message;
    html += '</div>';

    $(selector).prepend(html);

    app.scrollTop(app.formMessageHelper.config.formErrorMessageClass);

    app.timeoutFadeOut(app.formMessageHelper.config.formErrorMessageClass, 3000);
};


/**----------------------------------------------------------------------
 * [DISPLAY FORM MESSAGE]
 * -Display form message
 * 
 * @param  {string}  selector    - Form selector
 * @param  {string}  message     - Form message to display
 * @param  {integer} statusCode  - Request status code	 
 * @return {any}
 *--------------------------------------------------------------*/
app.formMessage = function(selector, message, statusCode = 200) {
    // Remove the existing alert message form
    app.formMessageHelper.removeFormMessage(selector);

    // Show alert message form
    app.formMessageHelper.showFormMessage(selector, message, statusCode);
};