"use strict";

/**----------------------------------------------------------------------
 * [Initialize app.formErrorsHelper object]
 *--------------------------------------------------------------*/
app.formErrorsHelper = {};

/**----------------------------------------------------------------------
 * [Handle app.formErrorsHelper object configuration]
 *--------------------------------------------------------------*/
app.formErrorsHelper.config = {
	formGroupClass: '.form-group',
	formErrorMessageClass: '.form-alert-message',
	errorClass: '.error',
	messageClass: '.message'
};

/**----------------------------------------------------------------------
 * [REMOVE CURRENT ERRORS]
 * -Remove current error messages on each field
 * 
 * @param {string} selector - Field selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.remove = function(selector) {
    if($(selector).parents(app.formErrorsHelper.config.formGroupClass).hasClass(app.removeClassDot(app.formErrorsHelper.config.errorClass))) {
        $(selector).parents(app.formErrorsHelper.config.formGroupClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(selector).parents(app.formErrorsHelper.config.formGroupClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
    }
};

/**----------------------------------------------------------------------
 * [DROPZONE UPLOAD FORM VALIDATIONS]
 * -Handle dropzone upload form validations
 * 
 * @param {string} fieldName - Fieldname
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.dropzone = function(fieldName, message) {

    var fieldNameArr = fieldName.split('.');

    $.each($('.dropzone'), function() {
        var dzElement = $(this);
        // for dropzone error required
        if (dzElement.attr('data-field-name') == fieldName) {

            $('#'+dzElement.attr('id'))
                .append("<p class='"+app.removeClassDot(app.formErrorsHelper.config.messageClass)+" mb-0 dropzone-required-error'>"+message+"</p>")
                .addClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        
            $('#'+dzElement.attr('id'))
                .parents(app.formErrorsHelper.config.formGroupClass)
                .addClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        }
    });

    $.each($('.dropzone'), function() {
        var dzElement = $(this);

        // error validation for dropzone
        if (fieldNameArr.length > 1 && dzElement.attr('data-field-name') == fieldNameArr[0]) {

            var cntr = 0;

            $.each(dzElement.find('.dz-preview').find('>li'), function() {

                if(fieldNameArr[1] == cntr) {
                    $(this)
                        .append("<span class='"+app.removeClassDot(app.formErrorsHelper.config.messageClass)+"'>" + message.replace(fieldName, fieldNameArr[0]) + ' </span>')
                        .addClass('error');
                }

                cntr++;
            });

            $('#'+dzElement.attr('id')).parents(app.formErrorsHelper.config.formGroupClass)
                .addClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        }
    });
    
};

/**----------------------------------------------------------------------
 * [APPEND FIELD ERROR MESSAGE]
 * -Append field error message and display to the user
 * 
 * @param {string} selector - Field selector
 * @param {string} message - Field error message
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.append = function(fieldName, selector, message) {
	// Append error message in .form-group after the textbox element
    $(selector).parents(app.formErrorsHelper.config.formGroupClass)
        .append("<p class='"+app.removeClassDot(app.formErrorsHelper.config.messageClass)+"'>"+message+"</p>")
        .addClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
    
    app.formErrorsHelper.dropzone(fieldName, message);
};

/**----------------------------------------------------------------------
 * [REMOVE ERROR MESSAGE ON KEYUP EVENT]
 * -Remove error messages on keyup event
 * 
 * @param {string} formSelector - Form selector
 * @param {string} fieldSelector - Field selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.keyup = function(formSelector, fieldSelector) {
    $(fieldSelector).on('keyup', function() {
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(formSelector).find(app.formErrorsHelper.config.formErrorMessageClass).remove();
    });

    $(fieldSelector).on('change', function() {
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(formSelector).find(app.formErrorsHelper.config.formErrorMessageClass).remove();
    });

    $(fieldSelector).on('click', function() {
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(formSelector).find(app.formErrorsHelper.config.formErrorMessageClass).remove();
    });

    $('.dropzone .dz-default').on('change', function() {
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(fieldSelector).parents(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(formSelector).find(app.formErrorsHelper.config.formErrorMessageClass).remove();
    });
};

/**----------------------------------------------------------------------
 * [REMOVE FORM ERROR MESSAGE]
 * -Remove form error message before showing the another error
 * 
 * @param {string} selector - Form selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.removeFormMessage = function(selector) {
	if($(selector).find(app.formErrorsHelper.config.formErrorMessageClass)) {
        $(selector).find(app.formErrorsHelper.config.formErrorMessageClass).remove();
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
app.formErrorsHelper.showFormMessage = function(selector, message, statusCode) {
	var alertType = "warning";
	var successStatusCode = [200, 201];

	if(successStatusCode.includes(statusCode)) {
		alertType = "success";
	}

	var html = '<div class="alert alert-'+alertType+' '+app.removeClassDot(app.formErrorsHelper.config.formErrorMessageClass)+'">';
    html += message;
    html += '</div>';

    $(selector).prepend(html);

    app.scrollTop(app.formErrorsHelper.config.formErrorMessageClass);

    app.timeoutFadeOut(app.formErrorsHelper.config.formErrorMessageClass, 5000);
};

/**----------------------------------------------------------------------
 * [REMOVE ERRORS ON BUTTON CLICK]
 * -Remove errors when click the submit button
 * 
 * @param {string} selector - Form selector
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrorsHelper.removeErrorsOnClickButton = function(selector) {
	// Remove error message on button click
    $(selector).find('button').on('click', function() {
        $(selector).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).find(app.formErrorsHelper.config.messageClass).remove();
        $(selector).find(app.formErrorsHelper.config.formGroupClass+app.formErrorsHelper.config.errorClass).removeClass(app.removeClassDot(app.formErrorsHelper.config.errorClass));
        $(selector).find(app.removeClassDot(app.formErrorsHelper.config.formErrorMessageClass)).remove();
    });
};

/**----------------------------------------------------------------------
 * [DISPLAY AJAX FORM ERRORS]
 * -A ajax form error validation function that will parse the json response and display to each fields
 * 
 * @param {string} selector - The form selector
 * @param {json} validation - The json array response from the server form validation
 * @param {integer} statusCode - Request status code
 * @return {any}
 *--------------------------------------------------------------*/
app.formErrors = function(selector, validation, statusCode = 422) {
    $.each(validation.errors, function(fieldName, fieldErrors) 
    {
        $.each(fieldErrors, function(errorType, errorValue) {

            var fieldSelector = selector + " [name='"+fieldName+"']";

            // Fix for multi-select
            if(!$(fieldSelector).length) {
                fieldSelector = selector + " [name='"+fieldName+"[]']";
            }

            // For array value
            if(app.stringHasDot(fieldName)) {
                var newFieldName = fieldName.split('.');

                if(newFieldName[0] !== undefined) {
                    fieldSelector = selector + " [name='"+newFieldName[0]+"']";
                }

                if($(selector + " [name='"+newFieldName[0]+'['+newFieldName[1]+']'+"']").length) {
                    fieldSelector = selector + " [name='"+newFieldName[0]+'['+newFieldName[1]+']'+"']";
                } else {
                    fieldSelector = selector + " [name='"+newFieldName[0]+'["'+newFieldName[1]+'"]'+"']";

                    if(!$(fieldSelector).length) {
                        fieldSelector = selector + " [name='"+newFieldName[0]+'["'+newFieldName[1]+'"]'+"']";
                    }

                    if(!$(fieldSelector).length) {
                        fieldSelector = selector + " [name=\""+newFieldName[0]+'[\''+newFieldName[1]+'\']'+"\"]";
                    }
                }

                // Fix for multi-select
                if(!$(fieldSelector).length) {
                    fieldSelector = selector + " [name='"+newFieldName[0]+"[]']";
                }
            }

            app.formErrorsHelper.remove(fieldSelector);

            app.formErrorsHelper.append(fieldName, fieldSelector, errorValue);

            app.formErrorsHelper.keyup(selector, fieldSelector);
        });
    });

    app.scrollTop(selector);

    app.formErrorsHelper.removeFormMessage(selector);
    
    app.formErrorsHelper.showFormMessage(selector, validation.message, statusCode);

    app.formErrorsHelper.removeErrorsOnClickButton(selector);
};