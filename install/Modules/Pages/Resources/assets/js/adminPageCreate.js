"use strict";

app.adminPageCreate = {};

// handle app.adminPageCreate object configuration
app.adminPageCreate.config = {
    form: '#admin-page-create-form',
    btn: '#btn-create-page',
    route: '/admin/pages/create',
    btnSaveExit: '#btn-create-page-exit',
    btnSavePreview: '#btn-create-page-preview',
};

// get page data
app.adminPageCreate.data = function() {
    var data = $(this.config.form).serializeArray();
    const type = $(this.config.form + ' [name="type"]').val();
    if (type === 'section') {
        data.push({name: 'sections', value: JSON.stringify(app.adminPage.getSections())});
    } else {
        data.push({name: 'content', value: tinyMCE.get('page-content').getContent()});
    }

    return data;
};

// handle ajax request for email template create
app.adminPageCreate.process = function(href = '') {
    var $self = this;
    var $button = $($self.config.btn);
    var $content = $button.html();
    const $btnDP = $button.closest('.btn-group').find('.dropdown-toggle');

    $.ajax({
        type: 'POST',
        url: $self.config.route,
        data: $self.data(),
        beforeSend: function () {
            app.buttonLoader($button);
            $btnDP.addClass('disabled').attr('disabled', true);
        },
        success: function (response, textStatus, xhr) {
            app.backButtonContent($button, $content);
            $btnDP.removeClass('disabled').attr('disabled', false);
            if (href) {
                if (href === '/admin/pages') {
                    app.redirect(href);
                } else {
                    href = '/admin/pages/'+response.data.page.id+'/preview';
                    window.open(href, '_blank', '');
                    window.location = '/admin/pages/'+response.data.page.id+'/edit';
                }
            } else {
                window.location = '/admin/pages/'+response.data.page.id+'/edit';
            }
            app.notify(response.message);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            // Reset button
            app.backButtonContent($button, $content);
            $btnDP.removeClass('disabled').attr('disabled', false);
        }
    });
};

app.adminPageCreate.save = function(e) {
    e.preventDefault();
    this.process();
};

app.adminPageCreate.saveExit = function(e) {
    e.preventDefault();
    this.process('/admin/pages');
};

app.adminPageCreate.savePreview = function(e) {
    e.preventDefault();
    const parentBtn = $(this.config.btn);
    const btn = parentBtn.closest('.btn-group').find('button');
    this.process('/admin/pages/preview');
};

// initialize functions of app.adminPageCreate object
app.adminPageCreate.init = function() {
    $(this.config.btn).on('click', this.save.bind(this));
    $(this.config.btnSaveExit).on('click', this.saveExit.bind(this));
    $(this.config.btnSavePreview).on('click', this.savePreview.bind(this));
};

// initialize app.adminPageCreate object until the document is loaded
$(document).ready(app.adminPageCreate.init());
