"use strict";

// define app.adminEmailTemplates object
app.adminEmailTemplates = {}


// initialize functions for app.adminEmailTemplates object 
app.adminEmailTemplates.init = function() {
    
};

// handle email template group error
app.adminEmailTemplates.groupError = function(response) {

    if(response.errors.content) {
        if($('#create-email-template-content').parents('.col-md-12').find('.group-error').length) {
            $('#create-email-template-content').parents('.col-md-12').find('.group-error').remove();
        }

        $.each(response.errors.content, function(k,v) {
            $('#create-email-template-content').parents('.col-md-12').prepend('<p class="group-error">'+v+'</p>');
        });

        setTimeout(function() {
            $('.group-error').hide();
        }, 3000);
    }
};

// initialize app.adminEmailTemplates object until the document is loaded
$(document).ready(app.adminEmailTemplates.init());
