"use strict";

// define app.adminEmailTemplateCreate object
app.adminEmailTemplateCreate = {};

// handle app.adminEmailTemplateCreate object configuration
app.adminEmailTemplateCreate.config = {
    form: '#email-template-create-form',
    route: '/admin/email-templates/create'
};

// initialize tinymce plugin
app.adminEmailTemplateCreate.tinyMCE = function() {
    tinymce.init({
        selector: 'textarea#create-email-template-content',
        plugins: ['preview', 'importcss', 'code', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons'],
        mobile: {
            plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons']
        },
        menubar: 'file edit view insert format tools table tc',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat | insertfile image media link anchor codesample | a11ycheck ltr rtl | tpl_button',
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        importcss_append: true,
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        toolbar_mode: 'sliding',
        spellchecker_ignore_list: ['Ephox', 'Moxiecode'],
        contextmenu: 'link image table',
        content_css: ["/plugins/tinymce/css/email-templates.css"],
        a11y_advanced_options: true,
        promotion: false,
        setup: function (editor) {
            editor.ui.registry.addButton('tpl_button', {
                text: app.trans('Add button'),
                onAction: function() {
                    // Open a Dialog
                    editor.windowManager.open({
                        title: app.trans('Add button'),
                        body: {
                            type: 'panel',
                            items: [{
                                type: 'input',
                                name: 'button_label',
                                label: app.trans('Button label (put your own {shortcode} if you want dynamic value)'),
                                flex: true
                            },{
                                type: 'input',
                                name: 'button_link',
                                label: app.trans('Button link (put your own {shortcode} if you want dynamic value)'),
                                flex: true
                            }, {
                                type: 'selectbox',
                                name: 'button_style',
                                label: app.trans('Button style'),
                                items: [
                                    {text: app.trans('Primary'), value: 'button button-primary'},
                                    {text: app.trans('Success'), value: 'button button-success'},
                                    {text: app.trans('Error'), value: 'button button-error'}
                                ],
                                flex: true
                            }]
                        },
                        onSubmit: function (api) {
                            var template = '<table class="action" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 30px auto; padding: 0; text-align: center; width: 100%;" role="presentation" width="100%" cellspacing="0" cellpadding="0" align="center">';
                            template += '<tbody>';
                            template += '<tr>';
                            template += '<td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative;" align="center">';
                            template += '<table style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative;" role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0">';
                            template += '<tbody>';
                            template += '<tr>';
                            template += '<td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative;" align="center">';
                            template += '<table style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative;" role="presentation" border="0" cellspacing="0" cellpadding="0">';
                            template += '<tbody>';
                            template += '<tr>';
                            template += '<td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative;"><a class="button button-primary" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif, \'Apple Color Emoji\', \'Segoe UI Emoji\', \'Segoe UI Symbol\'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;" href="'+api.getData().button_link+'" target="_blank" rel="noopener noreferrer">'+api.getData().button_label+'</a></td>';
                            template += '</tr>';
                            template += '</tbody>';
                            template += '</table>';
                            template += '</td>';
                            template += '</tr>';
                            template += '</tbody>';
                            template += '</table>';
                            template += '</td>';
                            template += '</tr>';
                            template += '</tbody>';
                            template += '</table>';

                            // insert markup
                            editor.insertContent(template);

                            // close the dialog
                            api.close();
                        },
                        buttons: [
                            {
                                text: app.trans('Close'),
                                type: 'cancel',
                                onclick: 'close'
                            },
                            {
                                text: app.trans('Insert'),
                                type: 'submit',
                                primary: true,
                                enabled: true
                            }
                        ]
                    });
                }
            });
        }
    });
};

// get email template data
app.adminEmailTemplateCreate.data = function() {
    var data = $(this.config.form).serializeArray();
    data.push({name: 'content', value: tinyMCE.get('create-email-template-content').getContent()});
    return data;
};

// handle ajax request for email template create
app.adminEmailTemplateCreate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();

    $.ajax({
        type: 'POST',
        url: $self.config.route,
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(response.data.redirectTo);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            // Reset button
            app.backButtonContent($button, $content);
            // Display content error
            app.emailTemplates.groupError(response.responseJSON);
        }
    }); 
};

// call all available functions of app.adminEmailTemplateCreate object to initialize
app.adminEmailTemplateCreate.init = function() {
    $(this.config.form).on('submit', this.process.bind(this));

    if(typeof tinymce !== 'undefined' && $('#create-email-template-content').length) {
        app.adminEmailTemplateCreate.tinyMCE();
    }
};

// initialize app.adminEmailTemplateCreate object until the document is loaded
$(document).ready(app.adminEmailTemplateCreate.init());