"use strict";

// Handles app object config
app.config = {
    avatarDefault: '/users/avatar.png'
};

/**----------------------------------------------------------------------
 * [AJAX SETUP]
 * -setup ajax global configuration here
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.ajaxSetup = function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
};

/**----------------------------------------------------------------------
 * [CLOSE MODAL]
 * -Close bootstrap modal
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.closeModal = function() {
	$('[data-dismiss="modal"]').trigger('click');
};


/**----------------------------------------------------------------------
 * [TOAST NOTIFY - MESSAGE]
 * -Display toast message
 * 
 * @param {string} message
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.notify = function(message) {

    if(typeof $.fn.toast === 'undefined') {
        return;
    }

	if(message == '') {
		return;
	}

	var html = '<div class="d-flex justify-content-center align-items-center toast-notify">';
        html += '<div class="toast toast-notify-show toast-black" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000" data-autohide="true">';
        html += '<div class="toast-body">';
        html += message;
        html += '</div>';
        html += '</div>';
    	html += '</div>';

    // Remove existing element
    if ( $('body').find('.toast-notify') ) {
    	$('.toast-notify').remove();
    }

    // Show
    $('body').append(html);

    $('.toast-notify-show').toast('show');
};


/**----------------------------------------------------------------------
 * [TOAST NOTIFY ON LOAD - MESSAGE]
 * -Display toast message on page load
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.notifyOnLoad = function() {
    if($('.alert-notification').html()!== undefined) {
        setTimeout(function() {
            app.notify($('.alert-notification').html());
        },1000);
    }
};


/**----------------------------------------------------------------------
 * [TOAST NOTIFY - ERROR]
 * -Display errors for laravel validation and 400 error with message
 * 
 * @param {json} validation - The json array response from the server form validation
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.notifyError = function(validation) {
    if ( validation.errors ) {
        $.each(validation.errors, function(fieldName, fieldErrors) 
        {
            $.each(fieldErrors, function(errorType, errorValue) {
                app.notify(errorValue)
            });
        });
    } else {
        app.notify(validation.message)
    }
};


/**----------------------------------------------------------------------
 * [REDIRECT TO ANOTHER PAGE]
 * -Redirect to another using with the url value
 * 
 * @param {string} url - Url
 * @return {any}
 *--------------------------------------------------------------*/
app.redirect = function(route) {
    window.location = route;
};


/**----------------------------------------------------------------------
 * [SCROLL TOP TO TARGET DIV]
 * -Scroll top to target div
 * 
 * @param {string} selector
 * @return {any}
 *--------------------------------------------------------------*/
app.scrollTop = function(selector) {
    var target = $(selector);
    if (target.length) {
        var top = target.offset().top;

        if(top != 0) {
            $('html,body').animate({scrollTop: (top - 70)}, 500);
            return false;
        }
    }
};


/**----------------------------------------------------------------------
 * [TIMEOUT FADE OUT]
 * -Fade out element with timer
 * 
 * @param {string} selector
 * @param {string} timer - miliseconds value
 * @return {any}
 *--------------------------------------------------------------*/
app.timeoutFadeOut = function(selector, timer) {
    setTimeout(function() {
        $(selector).fadeOut(2000)
    }, timer);
};


/**----------------------------------------------------------------------
 * [INITIALIZE SELECT2]
 * - Use to initialize select2
 * 
 * @param string|optional placeholder - Your select placeholder
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.initSelect2 = function(placeholder = '') {
    if(typeof $.fn.select2 !== 'undefined') {
        if(placeholder != '') {
            $('.select2').select2({
                placeholder: placeholder
            });
        } else {
            $('.select2').select2();
        }
    }
};


/**----------------------------------------------------------------------
 * [TRANSLATE LANGUAGE]
 * -Use to display the translation by phrase given
 *
 * @return {any}
 *--------------------------------------------------------------*/
app.trans = function(phrase, replacers = {}) {
    if(app.translations[phrase] !== undefined) {
        phrase = app.translations[phrase];
    }

    for (var key in replacers) {
        var regex = new RegExp(":" + key, "g");
        var v = replacers[key];
        phrase = phrase.replace(regex, v);
    }

    return phrase;
};


/**----------------------------------------------------------------------
 * [BASIC GUARD CHECKING]
 * -Use the check if given permission is permitted
 * 
 * @return {boolean}
 *--------------------------------------------------------------*/
app.can = function(permission) {
    if(app.policies !== undefined) {
        return typeof app.policies[permission] !== 'undefined' && app.policies[permission];
    }
};


/**----------------------------------------------------------------------
 * [HAS DOT STRING]
 * -Check string if has a dot
 * 
 * @param {string} string
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.stringHasDot = function(string) {
    return string.includes('.');
};


/**----------------------------------------------------------------------
 * [RESET RECAPTCHA]
 * -Reset the recaptcha
 * 
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.recaptchaReset = function() {
    if(typeof grecaptcha !== 'undefined') {
        grecaptcha.reset();
    }
};


/**----------------------------------------------------------------------
 * - Convert obejct to uri encoded string
 *
 * Example 1: {name: "John", age: 40} to name=John&age=40
 *
 * @param {obj} object
 *
 * @return {string}
 *--------------------------------------------------------------*/
app.objectToUrlParams = function(obj) {
    var str = "";
    for (var key in obj) {
        if (str != "") {
            str += "&";
        }
        str += key + "=" + encodeURIComponent(obj[key]);
    }
    return str;
};


/**----------------------------------------------------------------------
 * [REMOVE CLASS DOT]
 * -Removing class dot
 * 
 * @param {string} className - Class name with dot
 * @return {any}
 *--------------------------------------------------------------*/
app.removeClassDot = function(className) {
    return className.replace('.', '');
};


/**----------------------------------------------------------------------
 * [AJAX BUTTON LOADER]
 * -display button loading on submitting request via ajax
 * 
 * @param {string} button - The button selector
 * @return {any}
 *--------------------------------------------------------------*/
app.buttonLoader = function(button) {
    button.attr('disabled', true).html('&nbsp;<i class=\"fas fa-spinner fa-spin\"></i>&nbsp;');
};


/**----------------------------------------------------------------------
 * [AJAX BUTTON CONTENT]
 * -revert the button content when ajax completed
 * 
 * @param {string} button - The button selector
 * @param {string} content - The button content
 * @return {any}
 *--------------------------------------------------------------*/
app.backButtonContent = function(button, content) {
    button.attr('disabled', false).html(content);
};


/**----------------------------------------------------------------------
 * [STRING TRUNCATE]
 * -handle truncating string if more than the maximum limit
 * 
 * @param {string} str - string value to truncate
 * @param {integer} n - string maximum limit
 * @return {any}
 *--------------------------------------------------------------*/
app.stringTruncate = function(str, n) {
    return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
};


/**----------------------------------------------------------------------
 * [INPUT MAX LENGHT]
 * -handle input max length 
 * 
 * @return {any}
 *--------------------------------------------------------------*/
app.inputMaxLength = function() {
    var $targetfields = $("input[data-max-size], textarea[data-max-size]");

    $targetfields.each(function(i) {
        var $field = $(this)
        $field.data('max-size', parseInt($field.attr('data-max-size')) || 20)
        $field.on('input propertychange', function(e) {
            var maxChar = $field.data('max-size');
            var length = $field.val().length;
            if (length >= $field.data('max-size')) {
                $field.val(app.strMaxLength($field.val(), maxChar));
            }

            const $parent = $field.closest('.form-group');
            if ($parent.length) {
                $parent.find('.character_length').html(length);
            }
        })
    });
};


/**----------------------------------------------------------------------
 * [STRING MAX LENGHT]
 * -handle string max length 
 * 
 * @param {string} string
 * @param {int} max
 * @return {any}
 *--------------------------------------------------------------*/
app.strMaxLength = function(string, max = 20) {
    return string.substring(0, max);
};


/**----------------------------------------------------------------------
 * Initialize default app object available methods
 *--------------------------------------------------------------*/
app.init = function() {
    app.ajaxSetup();
    app.notifyOnLoad();
    app.initSelect2();
    app.inputMaxLength();
};

/**----------------------------------------------------------------------
 * Encode string to slug
 *
 * @param {string} str - string maximum limit
 *--------------------------------------------------------------*/
app.slugify = function(str) {

    //replace all special characters | symbols with a space
    str = str.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ')
            .toLowerCase();

    // trim spaces at start and end of string
    str = str.replace(/^\s+|\s+$/gm,'');

    // replace space with dash/hyphen
    return str.replace(/\s+/g, '-');
}


/**----------------------------------------------------------------------
 * initialize app object until the document is loaded
 *--------------------------------------------------------------*/
$(document).ready(app.init());