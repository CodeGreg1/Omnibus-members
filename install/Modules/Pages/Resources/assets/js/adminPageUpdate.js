"use strict";

app.adminPageUpdate = {};

// handle app.adminPageUpdate object configuration
app.adminPageUpdate.config = {
    form: '#admin-page-update-form',
    btn: '#btn-update-page',
    btnSaveExit: '#btn-update-page-exit',
    btnSavePreview: '#btn-update-page-preview',
    route: '/admin/pages/update'
};

// get page data
app.adminPageUpdate.data = function() {
    var data = $(this.config.form).serializeArray();
    const type = $(this.config.form + ' [name="type"]').val();
    data.push({name: 'type', value: type});
    if (type === 'section') {
        data.push({name: 'sections', value: JSON.stringify(app.adminPage.getSections())});
    } else {
        data.push({name: 'content', value: tinyMCE.get('page-content').getContent()});
    }

    return data;
};

// handle ajax request for email template create
app.adminPageUpdate.process = function(href = '') {
    var $self = this;
    var $button = $($self.config.btn);
    var $content = $button.html();
    var route = $($self.config.form).data('route');
    const $btnDP = $button.closest('.btn-group').find('.dropdown-toggle');

    $.ajax({
        type: 'PATCH',
        url: route,
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
                    window.open(href, '_blank', '');
                    window.location = window.location.href;
                }
            } else {
                window.location = window.location.href;
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

app.adminPageUpdate.save = function(e) {
    e.preventDefault();
    this.process();
};

app.adminPageUpdate.saveExit = function(e) {
    e.preventDefault();
    this.process('/admin/pages');
};

app.adminPageUpdate.savePreview = function(e) {
    e.preventDefault();
    const parentBtn = $(this.config.btn);
    const btn = parentBtn.closest('.btn-group').find('button');
    this.process('/admin/pages/'+parentBtn.data('id')+'/preview');
};

// initialize functions of app.adminPageUpdate object
app.adminPageUpdate.init = function() {
    $(this.config.btn).on('click', this.save.bind(this));
    $(this.config.btnSaveExit).on('click', this.saveExit.bind(this));
    $(this.config.btnSavePreview).on('click', this.savePreview.bind(this));
};

// initialize app.adminPageUpdate object until the document is loaded
$(document).ready(app.adminPageUpdate.init());
