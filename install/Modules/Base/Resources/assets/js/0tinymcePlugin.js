"use strict";

app.tinymcePlugin = {};

app.tinymcePlugin.image = '';

app.tinymcePlugin.photoGallery = function() {
    tinymce.PluginManager.add('photoGallery', function(editor, url) {
        var openDialog = function () {
            if ($('#select-image-galley-modal').length) {
                $('#select-image-galley-modal').modal('show');
                window.photoGalleryTarget = 'tinyMce';
            }
        };

        $(document).delegate('[name="photoImageGallerySelectItem"]', 'change', function() {
            if (this.checked) {
                const $el = $(this).closest('.imagecheck');
                if (window.photoGalleryTarget === 'tinyMce') {
                    app.tinymcePlugin.image = {
                        id: this.value,
                        src: $el.find('img').data('src'),
                        preview: $el.find('img').attr('src'),
                        name: $el.find('img').attr('alt')
                    };
                }
            }
        });

        $('.btn-image-gallery-select').on('click', function(e) {
            if (app.tinymcePlugin.image) {
                editor.insertContent('<img src="'+app.tinymcePlugin.image.src+'" data-mce-src="'+app.tinymcePlugin.image.src+'" alt="'+app.tinymcePlugin.image.name+'">');
            }
        });

        /* Add a button that opens a window */
        editor.ui.registry.addButton('insertPhoto', {
            icon: 'gallery',
            onAction: function () {
            /* Open window */
            openDialog();
            }
        });
        /* Adds a menu item, which can then be included in any menu via the menu/menubar configuration */
        editor.ui.registry.addMenuItem('insertPhoto', {
            text: 'Photo gallery',
            onAction: function() {
            /* Open window */
            openDialog();
            }
        });

        /* Return the metadata for the help plugin */
        return {
            getMetadata: function () {
                return  {
                    name: 'Photo gallery',
                    url: 'http://exampleplugindocsurl.com'
                };
            }
        };
    });
};

app.tinymcePlugin.init = function() {
    if(typeof tinymce !== 'undefined') {
        app.tinymcePlugin.photoGallery();
    }
};

$(document).ready(app.tinymcePlugin.init());
