"use strict";

app.adminBlogCreate = {};

// handle app.adminBlogCreate object configuration
app.adminBlogCreate.config = {
    btn: '#btn-create-blog',
    form: '#blog-create-form',
    route: '/admin/blogs/create'
};

// get email template data
app.adminBlogCreate.data = function() {
    var data = $(this.config.form).serializeArray();
    data.push({name: 'content', value: tinyMCE.get('blog-content').getContent()});
    var images = $(`[data-image-gallery="1100"]`)
                            .sGallery()
                            .images();

    $(app.adminBlog.config.tagList).find('.tag-item').map(function() {
        data.push({
            name: 'tags[]',
            value: $(this).data('id')
        });
    });

    if (images.length) {
        data.push({
            name: 'media_id',
            value: images[0].id
        });
    }

    return data;
};

// handle ajax request for email template create
app.adminBlogCreate.process = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.btn);
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
        }
    });
};

// call all available functions of app.adminBlogCreate object to initialize
app.adminBlogCreate.init = function() {
    const self = this;
    $(this.config.btn).on('click', function() {
        $(self.config.form).submit();
    });
    $(this.config.form).on('submit', this.process.bind(this));
};

// initialize app.adminBlogCreate object until the document is loaded
$(document).ready(app.adminBlogCreate.init());
