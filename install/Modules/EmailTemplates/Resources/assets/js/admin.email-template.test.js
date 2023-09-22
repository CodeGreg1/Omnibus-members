"use strict";

// define app.adminEmailTemplateTest object
app.adminEmailTemplateTest = {};

// handle app.adminEmailTemplateTest object configuration
app.adminEmailTemplateTest.config = {
    get: {
        route: '/admin/email-templates/get'
    },
    send: {
       route: '/admin/email-templates/send',
       form: '#email-template-send-form'
    }
};

// handle getting data for ajax request of email template testing
app.adminEmailTemplateTest.data = function() {
    var data = $(this.config.send.form).serializeArray();
    return data;
};

// handle generating defined shortcodes
app.adminEmailTemplateTest.generateDefinedShortcodes = function(response) {
    var html = '<div class="form-group"><label>'+app.trans('Defined Shortcodes')+'</label>';
        html += '<table class="table table-bordered table-md">';
        html += '<tbody><tr>';
          html += '<th width="30%">'+app.trans('Shortcode')+'</th>';
          html += '<th>'+app.trans('Value')+'</th>';
        html += '</tr>';

        var defaultShortcodes = response.data.defaultShortcodes;

        $.each(response.data.definedShortcodes, function(k,v) {
            if(!defaultShortcodes.includes(v)) {
                html += '<tr>';
                  html += '<td>'+v+'</td>';
                  html += '<td><input type="text" name="shortcodes['+v+']" class="form-control"></td>';
                html += '</tr>';
            }
        });

    html += '</tbody></table></div>';

    $('#templateDefinedShortcodesTable').html(html);
};

// handle generate default shortcodes
app.adminEmailTemplateTest.generateDefaultShortcodes = function(response) {
    var html = '<div class="form-group"><label>'+app.trans('Default Shortcodes')+'</label>';
        html += '<table class="table table-bordered table-md">';
        html += '<tbody><tr>';
          html += '<th width="30%">'+app.trans('Shortcode')+'</th>';
          html += '<th>'+app.trans('Value')+'</th>';
        html += '</tr>';

        $.each(response.data.defaultShortcodesValues, function(k,v) {
            html += '<tr>';
              html += '<td>{'+k+'}</td>';
              html += '<td>'+v+'</td>';
            html += '</tr>';
        });

    html += '</tbody></table></div>';

    $('#templateDefaultShortcodesTable').html(html);
};

// handle selecting email template
app.adminEmailTemplateTest.select = function() {
    var $self = this;

    $('#selectTemplate').on('change', function() {

        var route = app.adminEmailTemplateTest.config.get.route;

        if($(this).val()) {

            $('#email-template-send-form button').attr('disabled', false);

            $.ajax({
                type: 'GET',
                url: route,
                data: {id: $(this).val()},
                success: function (response, textStatus, xhr) {
                    $('#sendToWrapper').show();
                    $($self.config.send.form + " #id").val(response.data.emailTemplate.id);

                    $('#templateViewer').html(response.data.emailTemplate.content);

                    app.adminEmailTemplateTest.generateDefinedShortcodes(response);

                    app.adminEmailTemplateTest.generateDefaultShortcodes(response);
                }
            });
        } else {
            $('#email-template-send-form button').attr('disabled', true);
            $('#sendToWrapper').hide();
            $($self.config.send.form + " #id").val('');
            $('#templateViewer').html('');
            $('#templateShortcodesTable').html('');
            $('#templateDefaultShortcodesTable').html('');
            $('#templateDefinedShortcodesTable').html('');
        }
    });
};

// handling ajax for sending email testing
app.adminEmailTemplateTest.send = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.send.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: $self.config.send.route,
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
        },
        complete: function (xhr) {
            app.backButtonContent($button, $content);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.send.form, response.responseJSON, response.status);
        }
    }); 
};

// call all available functions of app.adminEmailTemplateTest object to initialize
app.adminEmailTemplateTest.init = function() {
    app.adminEmailTemplateTest.select();
    $(this.config.send.form).on('submit', this.send.bind(this));
};

// initialize app.adminEmailTemplateTest object until the document is loaded
$(document).ready(app.adminEmailTemplateTest.init());