(function() {
    "use strict";

    const btn = '#contact-us-submit-button';
    const form = '#contact-us-form';

    const notify = function(selector, message) {
        if ($(selector).prev().hasClass('alert')) {
            $(selector).prev().remove();
        }

        const html = `<div class="alert alert-info alert-dismissible show fade">
                      <div class="alert-body">
                        ${message}
                      </div>
                    </div>`;
        $(html).insertBefore(selector);

        $('[data-dismiss="alert"]').on('click', function() {
            $(this).closest('.alert').remove();
        });

        setTimeout(function() {
            if ($(selector).prev().hasClass('alert')) {
                $(selector).prev().remove();
            }
        }, 6000);
    };

    const buttonLoader = function(button) {
        button.attr('disabled', true).html('&nbsp;<i class=\"fas fa-spinner fa-spin\"></i>&nbsp;');
    };

    const backButtonContent = function(button, content) {
        button.attr('disabled', false).html(content);
    };

    const formErrorsHelper = {
        config: {
            formGroupClass: '.form-group',
            formErrorMessageClass: '.form-alert-message',
            errorClass: '.error',
            messageClass: '.message'
        },
        remove: function(selector) {
            if($(selector).parents(formErrorsHelper.config.formGroupClass).hasClass(formErrorsHelper.config.errorClass.replace('.', ''))) {
                $(selector).parents(formErrorsHelper.config.formGroupClass).find(formErrorsHelper.config.messageClass).remove();
                $(selector).parents(formErrorsHelper.config.formGroupClass).removeClass(formErrorsHelper.config.errorClass.replace('.', ''));
            }
        },

        append: function(fieldName, selector, message) {
            // Append error message in .form-group after the textbox element
            $(selector).parents(formErrorsHelper.config.formGroupClass)
                .append("<p class='"+formErrorsHelper.config.messageClass.replace('.', '')+"'>"+message+"</p>")
                .addClass(formErrorsHelper.config.errorClass.replace('.', ''));
        },
        keyup: function(formSelector, fieldSelector) {
            $(fieldSelector).on('keyup', function() {
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).find(formErrorsHelper.config.messageClass).remove();
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).removeClass(formErrorsHelper.config.errorClass.replace('.', ''));
                $(formSelector).find(formErrorsHelper.config.formErrorMessageClass).remove();
            });

            $(fieldSelector).on('change', function() {
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).find(formErrorsHelper.config.messageClass).remove();
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).removeClass(formErrorsHelper.config.errorClass.replace('.', ''));
                $(formSelector).find(formErrorsHelper.config.formErrorMessageClass).remove();
            });

            $(fieldSelector).on('click', function() {
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).find(formErrorsHelper.config.messageClass).remove();
                $(fieldSelector).parents(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).removeClass(formErrorsHelper.config.errorClass.replace('.', ''));
                $(formSelector).find(formErrorsHelper.config.formErrorMessageClass).remove();
            });
        },
        removeFormMessage: function(selector) {
            if($(selector).find(formErrorsHelper.config.formErrorMessageClass)) {
                $(selector).find(formErrorsHelper.config.formErrorMessageClass).remove();
            }
        },

        showFormMessage: function(selector, message, statusCode) {
            var alertType = "warning";
            var successStatusCode = [200, 201];

            if(successStatusCode.includes(statusCode)) {
                alertType = "success";
            }

            var html = '<div class="alert alert-'+alertType+' '+formErrorsHelper.config.formErrorMessageClass.replace('.', '')+'">';
            html += message;
            html += '</div>';

            $(selector).prepend(html);

            setTimeout(function() {
                $(formErrorsHelper.config.formErrorMessageClass).fadeOut(2000)
            }, 5000);
        },

        removeErrorsOnClickButton: function(selector) {
            // Remove error message on button click
            $(selector).find('button').on('click', function() {
                $(selector).find(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).find(formErrorsHelper.config.messageClass).remove();
                $(selector).find(formErrorsHelper.config.formGroupClass+formErrorsHelper.config.errorClass).removeClass(formErrorsHelper.config.errorClass.replace('.', ''));
                $(selector).find(formErrorsHelper.config.formErrorMessageClass.replace('.', '')).remove();
            });
        }
    };

    const formErrors = function(selector, validation, statusCode = 422) {
        $.each(validation.errors, function(fieldName, fieldErrors)
        {
            $.each(fieldErrors, function(errorType, errorValue) {

                var fieldSelector = selector + " [name='"+fieldName+"']";

                // Fix for multi-select
                if(!$(fieldSelector).length) {
                    fieldSelector = selector + " [name='"+fieldName+"[]']";
                }

                // For array value
                if(fieldName.includes('.')) {
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

                formErrorsHelper.remove(fieldSelector);

                formErrorsHelper.append(fieldName, fieldSelector, errorValue);

                formErrorsHelper.keyup(selector, fieldSelector);
            });
        });

        formErrorsHelper.removeFormMessage(selector);

        formErrorsHelper.showFormMessage(selector, validation.message, statusCode);

        formErrorsHelper.removeErrorsOnClickButton(selector);
    };

    const process = function(e) {
        e.preventDefault();

        const route = $(form).data('route');

        var $button = $(btn);
        var $content = $button.html();

        var data = $(form).serializeArray();
        data.push({
            name: 'redirectTo',
            value: window.location.href
        });

        $.ajax({
            type: 'POST',
            url: route,
            data: data,
            beforeSend: function () {
                buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                $(form)[0].reset();
                backButtonContent($button, $content);

                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }

                notify(form, response.message);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                formErrors(form, response.responseJSON, response.status);
                // Reset button
                backButtonContent($button, $content);

                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            }
        });
    };

    $(document).ready(function() {
        $(btn).on('click', function() {
            $(form).submit();
        });

        $(form).on('submit', process.bind(this));
    });
})();
