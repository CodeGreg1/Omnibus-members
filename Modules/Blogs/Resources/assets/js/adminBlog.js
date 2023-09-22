"use strict";

app.adminBlog = {};

app.adminBlog.config = {
    tagSearchInpt: '.tagsinput-search',
    tagInpt: '.tagsinput',
    tagList: '.tag-list',
    tagItemRemoveBtn: '.tag-item-remove-btn',
    tagSearchTimeout: null,
    tagSearchItem: '.available-tag-item',
    toggleSeoSetting: '.toggle-blog-seo-settings'
};

// initialize tinymce plugin
app.adminBlog.tinyMCE = function() {
    tinymce.init({
        selector: 'textarea#blog-content',
        plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'photoGallery', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons'],
        mobile: {
            plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons']
        },
        menubar: 'file edit view insert format tools table tc',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat | insertPhoto image media link anchor codesample | a11ycheck ltr rtl',
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
            editor.on('init', function(e) {
                $('#btn-update-blog').removeClass('d-none');
            });
        }
    });
};

app.adminBlog.searchTag = function() {
    const self = this;
    $(self.config.tagSearchInpt).on('input', function(e) {
        var tag = this.value.trim();
        var $btn = $(this);
        const $DP = $(this).closest('.dropdown');
        if (tag.length >= 2) {
            clearTimeout(self.config.tagSearchTimeout);
            self.config.tagSearchTimeout = setTimeout(function() {
               $.ajax({
                    type: 'GET',
                    url: '/admin/tags/search?name='+tag,
                    beforeSend: function () {

                    },
                    success: function (response, textStatus, xhr) {
                        $DP.find('.available-tag-item').remove();
                        if (response.length) {
                            for (let index = 0; index < response.length; index++) {
                                const tag = response[index];
                                $DP.find('.dropdown-menu').append(`<a class="dropdown-item available-tag-item" href="javascript:void(0)" data-id="${tag.id}" data-name="${tag.name}">${tag.name}</a>`);
                            }

                            $btn.data('expanded', true);
                            $DP.addClass('show');
                            $DP.find('.dropdown-menu').addClass('show');
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $DP.find('.available-tag-item').remove();
                        $btn.data('expanded', false);
                        $DP.removeClass('show');
                        $DP.find('.dropdown-menu').removeClass('show');
                    }
                });
            }, 500);
        } else {
            $DP.find('.available-tag-item').remove();
            $btn.data('expanded', false);
            $DP.removeClass('show');
            $DP.find('.dropdown-menu').removeClass('show');
        }
    });
};

app.adminBlog.searchTagItemSelect = function() {
    const self = this;
    $(document).delegate(self.config.tagSearchItem, 'click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        if ($(self.config.tagList).find('[data-name="'+name+'"]').length) {
            return;
        }

        self.addTagItem({
            id: id,
            name: name
        });
    });
};

app.adminBlog.addTag = function() {
    const self = this;
    $(self.config.tagSearchInpt).keypress(function(e) {
        if (e.keyCode == 13) {
            const name = e.target.value.trim();
            if ($(self.config.tagList).find('[data-name="'+name+'"]').length) {
                return;
            }

            $.ajax({
                type: 'POST',
                url: '/admin/tags/create',
                data: {name},
                beforeSend: function () {
                    $(self.config.tagSearchInpt).attr('disabled', true);
                },
                success: function (response, textStatus, xhr) {
                    $(self.config.tagSearchInpt).attr('disabled', false);
                    self.addTagItem(response.data.tag);
                    $(self.config.tagSearchInpt).val('');
                    $(self.config.tagSearchInpt).focus();
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $(self.config.tagSearchInpt).attr('disabled', false);
                }
            });
        }
    });
};

app.adminBlog.addTagItem = function(item) {
    if ($(this.config.tagList).find('[data-name="'+item.name+'"]').length) {
        return;
    }

    $(this.config.tagList).removeClass('no-tag-list');
    $(this.config.tagList).find('.no-tags').remove();
    $(this.config.tagList).append(`<div class="tag-item" data-id="${item.id}" data-name="${item.name}">
                                    <span class="tag-item-name">${item.name}</span>
                                    <a href="javascript:void(0)" class="tag-item-remove-btn">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>`);
};

app.adminBlog.removeTagItem = function() {
    const self = this;
    $(document).delegate(self.config.tagItemRemoveBtn, 'click', function() {
        $(this).parent('.tag-item').remove();
        if (!$(self.config.tagList).find('.tag-item').length) {
            $(self.config.tagList).addClass('no-tag-list');
            $(self.config.tagList).html(`<div class="no-tags">${app.trans('No tags added')}</div>`);
        }
    });
};

app.adminBlog.pageTitleInput = function() {
    if ($('.admin-blog-form [name="page_title"]').length) {
        $('.admin-blog-form .page_title_length').html($('.admin-blog-form [name="page_title"]').val().length);
        $('.admin-blog-form [name="page_title"]').on('input', function() {
            $('.admin-blog-form .page_title_length').html(this.value.length);
        });
    }

    if ($('.admin-blog-form [name="page_description"]').length) {
        $('.admin-blog-form page_description_length').html($('.admin-blog-form [name="page_description"]').val().length);
        $('.admin-blog-form [name="page_description"]').on('input', function() {
            $('.admin-blog-form .page_description_length').html(this.value.length);
        });
    }
};

app.adminBlog.toggleSeoSettings = function() {
    $(this.config.toggleSeoSetting).on('click', function() {
        $(this).remove();
        $('.admin-blog-form .blog-seo-edit').removeClass('display-none');
    });
};

app.adminBlog.hasPageTitle = function() {
    if ($('.admin-blog-form [name="title"]').length && $('.admin-blog-form [name="title"]').val().trim()) {
        return true;
    }

    if ($('.admin-blog-form [name="page_title"]').length && $('.admin-blog-form [name="page_title"]').val().trim()) {
        return true;
    }

    return false;
};

app.adminBlog.hasPageDescription = function() {
    if ($('.admin-blog-form [name="description"]').length && $('.admin-blog-form [name="description"]').val().trim()) {
        return true;
    }

    if ($('.admin-blog-form [name="page_description"]').length && $('.admin-blog-form [name="page_description"]').val().trim()) {
        return true;
    }

    return false;
};

app.adminBlog.toggleSeoPreviewTitle = function() {
    if (this.hasPageTitle()) {
        $('.admin-blog-form .blog-seo-preview-title-desc').addClass('d-none');
        $('.admin-blog-form .blog-seo-preview-title-desc-value').removeClass('d-none');
    } else {
        $('.admin-blog-form .blog-seo-preview-title-desc').removeClass('d-none');
        $('.admin-blog-form .blog-seo-preview-title-desc-value').addClass('d-none');
    }
};

app.adminBlog.seoSettingInpt = function() {
    const self = this;

    $('.admin-blog-form [name="title"]').on('input', function() {
        if ($('.admin-blog-form [name="page_title"]').length) {
            $('.admin-blog-form [name="page_title"]').attr('placeholder', app.strMaxLength(this.value, 70));

            if (!$('.admin-blog-form [name="page_title"]').val().trim()) {
                $('.admin-blog-form .blog-seo-preview-title').html(app.strMaxLength(this.value, 70));
                self.toggleSeoPreviewTitle();
            }
        }

        if ($('.admin-blog-form [name="slug"]').length) {

            if (!$('.admin-blog-form [name="slug"]').val().trim()) {
                $('.admin-blog-form [name="slug"]').attr('placeholder', app.slugify(app.strMaxLength(this.value, 70)));
                $('.admin-blog-form .blog-seo-preview-url')
                    .html(window.location.origin + '/blogs/'+app.slugify(app.strMaxLength(this.value, 70)));
                self.toggleSeoPreviewTitle();
            }
        }
    });

    $('.admin-blog-form [name="description"]').on('input', function() {
        if ($('.admin-blog-form [name="page_description"]').length) {
            $('.admin-blog-form [name="page_description"]').attr('placeholder', app.strMaxLength(this.value.trim(), 320));

            if ($('.admin-blog-form .blog-seo-edit').hasClass('display-none')) {
                $('.admin-blog-form [name="page_description"]').val(app.strMaxLength(this.value, 320));
                $('.admin-blog-form .blog-seo-preview-desc').html(app.strMaxLength(this.value, 320));
            }

            if (!$('.admin-blog-form [name="page_description"]').val().trim()) {
                $('.admin-blog-form .blog-seo-preview-desc').html(app.strMaxLength(this.value, 320));
            }

            self.toggleSeoPreviewTitle();
        }
    });

    $('.admin-blog-form [name="page_title"]').on('input', function() {
        var val = this.value.trim();
        if (val) {
            $('.admin-blog-form .blog-seo-preview-title').html(app.strMaxLength(val, 70));
        } else {
            val = $('.admin-blog-form [name="title"]').length ? $('.admin-blog-form [name="title"]').val().trim() : '';
            $('.admin-blog-form .blog-seo-preview-title').html(app.strMaxLength(val, 70));
        }
        self.toggleSeoPreviewTitle();
    });

    $('.admin-blog-form [name="page_description"]').on('input', function() {
        var val = this.value.trim();
        if (val) {
            $('.admin-blog-form .blog-seo-preview-desc').html(app.strMaxLength(val, 320));
        } else {
            $('.admin-blog-form .blog-seo-preview-desc').html(app.strMaxLength($('.admin-blog-form [name="description"]').val().trim(), 320));
        }
        self.toggleSeoPreviewTitle();
    });

    $('.admin-blog-form [name="page_description"]').on('focus', function() {
        if (!this.value.trim()) {
            var val = $('.admin-blog-form [name="description"]').val().trim();
            $(this).val(val);
            $('.admin-blog-form .blog-seo-preview-desc').html(app.strMaxLength(val, 320));

            self.toggleSeoPreviewTitle();
        }
    });

    $('.admin-blog-form [name="slug"]').on('input propertychange', function() {
        this.value = this.value.replace(/\s+/g, '-');

        $('.admin-blog-form .blog-seo-preview-url')
            .html(window.location.origin + '/blogs/'+app.slugify(app.strMaxLength(this.value, 70)));

        var val = this.value.trim();
        if (val) {
            $('.admin-blog-form .blog-seo-preview-url')
            .html(window.location.origin + '/blogs/'+app.slugify(app.strMaxLength(this.value, 70)));
        } else {
            val = $('.admin-blog-form [name="title"]').length ? $('.admin-blog-form [name="title"]').val().trim() : '';
            $('.admin-blog-form .blog-seo-preview-url')
            .html(window.location.origin + '/blogs/'+app.slugify(app.strMaxLength(val, 70)));
        }

        self.toggleSeoPreviewTitle();
    });
};

// call all available functions of app.adminEmailTemplateCreate object to initialize
app.adminBlog.init = function() {
    if(typeof tinymce !== 'undefined' && $('#blog-content').length) {
        app.adminBlog.tinyMCE();
    }

    this.searchTag();
    this.addTag();
    this.removeTagItem();
    this.searchTagItemSelect();
    this.pageTitleInput();
    this.seoSettingInpt();
    this.toggleSeoSettings();

    $('#tagSearchDropdown').on('shown.bs.dropdown', function () {
        if (!$(this).find('.available-tag-item').length) {
            $(this).find('.dropdown-toggle').data('expanded', false);
            $(this).removeClass('show');
            $(this).find('.dropdown-menu').removeClass('show');
        }
    });
};

// initialize app.adminEmailTemplateCreate object until the document is loaded
$(document).ready(app.adminBlog.init());
