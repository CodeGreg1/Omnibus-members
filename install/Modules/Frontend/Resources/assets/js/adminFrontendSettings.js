"use strict";

app.adminFrontendSettings = {};

// handle app.adminSettingsCreate object configuration
app.adminFrontendSettings.config = {
    form: '#admin-frontend-settings-form',
    redirect: '/admin/frontends/settings',
    types: {
        general: {
            getData: function(form) {
                var data = new FormData(form);

                var images = $(`[data-image-gallery="2100"]`)
                                        .sGallery()
                                        .images();

                if (images.length) {
                    data.append('frontend_social_sharing_image', images[0].original_url);
                } else {
                    data.append('frontend_social_sharing_image', '');
                }

                return data;
            }
        },
        footer_section: {
            getData: function(form) {
                var data = new FormData(form);

                var images = $(`[data-image-gallery="2200"]`)
                                        .sGallery()
                                        .images();

                return data;
            }
        },
        logos: {
            getData: function(form) {
                var data = new FormData(form);

                var headerImages = $(`[data-image-gallery="2301"]`)
                                        .sGallery()
                                        .images();

                if (headerImages.length) {
                    data.append('frontend_dark_logo', headerImages[0].original_url);
                } else {
                    data.append('frontend_dark_logo', '');
                }

                var footerImages = $(`[data-image-gallery="2302"]`)
                                        .sGallery()
                                        .images();

                if (footerImages.length) {
                    data.append('frontend_white_logo', footerImages[0].original_url);
                } else {
                    data.append('frontend_white_logo', '');
                }

                return data;
            }
        },
        header_section: {
            getData: function(form) {
                var data = new FormData(form);
                var headerImages = $(`[data-image-gallery="frontend-breadcrumb-bg-image"]`)
                                    .sGallery()
                                    .images();

                if (headerImages.length) {
                    data.append('frontend_breadcrumb_bg_image', headerImages[0].original_url);
                } else {
                    data.append('frontend_breadcrumb_bg_image', '');
                }

                return data;
            }
        }
    }
};

// handle app.adminFrontendSettings object ajax request
app.adminFrontendSettings.ajax = function() {
    const self = this;
    $(self.config.form).on('submit', function(e) {

        e.preventDefault();

        var $button = $(this).find(':submit');
        var $content = $button.html();
        const route = $(this).data('route');
        var type = $(this).data('type'), data;

        if (self.config.types.hasOwnProperty(type) && self.config.types[type].hasOwnProperty('getData')) {
            data = self.config.types[type].getData(this);
        } else {
            data = new FormData(this);
        }

        $.ajax({
            type: 'POST',
            url: route,
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);

                setTimeout(function() {
                    var redirect = $(self.config.form + ' [name="redirect"]');

                    if(!redirect.length) {
                        redirect = self.config.redirect;
                    } else {
                        redirect = redirect.val();
                    }

                    app.redirect(redirect);
                }, 1000);

                app.backButtonContent($button, $content);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(self.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });


    });

};

app.adminFrontendSettings.headerBgStyleChange = function() {
    $('[name="frontend_breadcrumb_background_style"]').on('change', function() {
        if (this.value === 'image') {
            $('.frontend-breadcrumb-bg-color').addClass('d-none');
            $('.frontend-breadcrumb-bg-image').removeClass('d-none');
        } else {
            $('.frontend-breadcrumb-bg-color').removeClass('d-none');
            $('.frontend-breadcrumb-bg-image').addClass('d-none');
        }
    });
};

// initialize functions of app.adminFrontendSettings object
app.adminFrontendSettings.init = function() {
    app.adminFrontendSettings.ajax();
    app.adminFrontendSettings.headerBgStyleChange();
};

// initialize app.adminFrontendSettings object until the document is loaded
$(document).ready(app.adminFrontendSettings.init());
