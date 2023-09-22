"use strict";

// define app.adminLanguagesAddLanguagePhrase object
app.adminLanguagesAddLanguagePhrase = {};

// handle app.adminLanguagesAddLanguagePhrase object configuration
app.adminLanguagesAddLanguagePhrase.config = {
    form: '#add-language-phrase-form'
};

// handle getting route for adding language add phrase
app.adminLanguagesAddLanguagePhrase.route = function() {
    return $(app.adminLanguagesAddLanguagePhrase.config.form).data('action');
};

// handle key element event on keyup and set the language value
app.adminLanguagesAddLanguagePhrase.key = function() {
    $(app.adminLanguagesAddLanguagePhrase.config.form + ' [name="key"]').on('change', function() {
        var that = $(this);
        var route = that.attr('data-action');
        var data = $('#add-language-phrase-form').serialize();

        $.ajax({
            type: 'POST',
            url: route,
            data: data,
            cache: false,
            beforeSend: function() {
                $(app.adminLanguagesAddLanguagePhrase.config.form + ' .language-value').val('');//reset

                that.parent('.form-group').append('<p class="ajax-translating">Translating...</p>');
            },
            success: function (response) {
                $('.ajax-translating').hide();

                $.each(response.data.value, function(key, value) {
                    $(app.adminLanguagesAddLanguagePhrase.config.form + ' .language-value-'+key).val(value);
                });
            }
        });
    });
};

// handle keyup on english element and get key value
app.adminLanguagesAddLanguagePhrase.english = function() {
    $(app.adminLanguagesAddLanguagePhrase.config.form + ' [name="value[\'en\']"]').on('keyup', function() {
        $(app.adminLanguagesAddLanguagePhrase.config.form + ' [name="key"]').val($(this).val());
    });
};

// handle ajax request on saving language phrase
app.adminLanguagesAddLanguagePhrase.ajax = function() {

    $(app.adminLanguagesAddLanguagePhrase.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminLanguagesAddLanguagePhrase.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminLanguagesAddLanguagePhrase.route(),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                // Close modal
                app.closeModal();
                // Reset button
                app.backButtonContent($button, $content);
                // Reset form
                app.formReset(app.adminLanguagesAddLanguagePhrase.config.form);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminLanguagesAddLanguagePhrase.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

// initialize functions of app.adminLanguagesAddLanguagePhrase object
app.adminLanguagesAddLanguagePhrase.init = function() {
    app.adminLanguagesAddLanguagePhrase.ajax();
    app.adminLanguagesAddLanguagePhrase.key();
    app.adminLanguagesAddLanguagePhrase.english();
};

// initialize app.adminLanguagesAddLanguagePhrase object until the document is loaded
$(document).ready(app.adminLanguagesAddLanguagePhrase.init());