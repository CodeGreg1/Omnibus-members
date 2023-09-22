"use strict";

// define app.adminTickets object
app.adminTickets = {};

// handle on app.adminTickets object to initialize tinyMCE plugin
app.adminTickets.tinyMCE = function(selector = null) {

    if(selector === null) {
        selector = 'textarea.ticket-reply-tinymce-default';
    }

    tinymce.init({
        selector: selector,
        plugins: ['preview', 'importcss', 'code', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons'],
        mobile: {
            plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons']
        },
        menubar: 'file edit view insert format tools table tc',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat | insertfile image media link anchor codesample | a11ycheck ltr rtl',
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        height: 300,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        toolbar_mode: 'sliding',
        spellchecker_ignore_list: ['Ephox', 'Moxiecode'],
        contextmenu: 'link image table',
        a11y_advanced_options: true,
        promotion: false,
        setup: function (editor) {
            editor.on('change', function(e) {
                var editorId = tinyMCE.activeEditor.id;
                // remove error message on change event
                if($('#'+editorId).parent('.form-group').hasClass('error')) {
                    $('#'+editorId).parent('.form-group').removeClass('error');
                    $('#'+editorId).parent('.form-group').find('.message').remove();
                }
           });
        }
    });
};

// initialize functions of app.adminTickets object
app.adminTickets.init = function() {
    if(typeof tinymce !== 'undefined' && $('.ticket-reply-tinymce-default').length) {
        app.adminTickets.tinyMCE();
    }
};

// initialize app.adminTickets object until the document is loaded
$(document).ready(app.adminTickets.init());