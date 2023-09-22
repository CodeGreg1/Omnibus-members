"use strict";

// define app.personalAccessToken object
app.personalAccessToken = {};

// handle app.personalAccessToken object configuration
app.personalAccessToken.config = {
    createButton: '.btn-revoke-personal-access-token',
    createForm: '#add-new-personal-token-form',
    createRoute: '/profile/personal-access-tokens/create',
    revokeButton: '.btn-revoke-personal-access-token'
};

// handle app.personalAccessToken object ajax request on creating token
app.personalAccessToken.create = function() {
    $(app.personalAccessToken.config.createForm).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.personalAccessToken.config.createForm).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.personalAccessToken.config.createRoute,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                
                app.closeModal();

                var message = "<span class='text-warning text-bold'>" + app.trans("Here is your new personal access token. Please take note that this is the only time will be shown so don't lose it! You may now use this token to make API requests.") + "</span>";
                message += "<p class='token-generated'>" + response.data.token + "</p>";

                bootbox.alert({
                    closeButton: false,
                    title: app.trans('Personal Access Token'),
                    message: message,
                    callback: function (result) {
                        app.redirect('/profile/personal-access-tokens');
                    }
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.personalAccessToken.config.createForm, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
};

// handle app.personalAccessToken object ajax request on revoking token
app.personalAccessToken.revoke = function(e) {

    $(document).delegate(app.personalAccessToken.config.revokeButton, 'click', function() {

        var $button = $(this);
        var $content = $button.html();

        bootbox.confirm({
            title: app.trans("Are you sure?"),
            message: app.trans("You're about to revoke this token."),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/profile/personal-access-tokens/revoke',
                        data: { id: $button.attr('data-id') },
                        beforeSend: function () {
                            app.buttonLoader($button);
                        },
                        success: function (response, textStatus, xhr) {
                            app.redirect('/profile/personal-access-tokens');
                        },
                        error: function (response) {
                            app.notify(response.responseJSON.message);
                            app.backButtonContent($button, $content);
                        }
                    });
                }
            }
        });
    });
     
};

// handle app.personalAccessToken object selecting generated token on click
app.personalAccessToken.select = function() {
    $(document).delegate('.token-generated', 'click', function() {
        var sel, range;
        var el = $(this)[0];
        if (window.getSelection && document.createRange) { //Browser compatibility
          sel = window.getSelection();
          if (sel.toString() == '') { //no text selection
             window.setTimeout(function(){
                range = document.createRange(); //range object
                range.selectNodeContents(el); //sets Range
                sel.removeAllRanges(); //remove all ranges from selection
                sel.addRange(range);//add Range to a Selection.
            }, 1);
          }
        } else if (document.selection) { //older ie
            sel = document.selection.createRange();
            if (sel.text == '') { //no text selection
                range = document.body.createTextRange();//Creates TextRange object
                range.moveToElementText(el);//sets Range
                range.select(); //make selection.
            }
        }
    })
    
};

// initialize functions of app.personalAccessToken object
app.personalAccessToken.init = function() {
    app.personalAccessToken.create();
    app.personalAccessToken.revoke();
    app.personalAccessToken.select();
};

// initialize app.personalAccessToken object until the document is loaded
$(document).ready(app.personalAccessToken.init());