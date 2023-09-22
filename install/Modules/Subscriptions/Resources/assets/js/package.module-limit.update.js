app.updatePackageModuleLimits = {};

app.updatePackageModuleLimits.config = {
    button: '#btn-update-package-module-limits'
};

app.updatePackageModuleLimits.data = function() {
    var limits = $('.package-permission-module-select:checked').map(function(i, item) {
        var tr = $(item).parents('tr');
        var input = tr.find('.package-permission-limit');
        var select = tr.find('.package-permission-term');
        return {
            permission_id: parseInt(item.dataset.id),
            limit: input.val(),
            term: select.val()
        };
    })
    .get();

    return {limits};
};

app.updatePackageModuleLimits.ajax = function(e) {
    var self = this;
    var button = $(e.target);
    var route = button.data('route');

    $.ajax({
        type: 'POST',
        url: route,
        data: self.data(),
        beforeSend: function () {
            app.buttonLoader(button);
        },
        success: function (response, textStatus, xhr) {
            app.notify(response.message);
            if (response.data.redirectTo) {
                app.redirect(response.data.redirectTo);
            }
        },
        complete: function (xhr) {
            app.backButtonContent(button, content);
            button.attr('disabled', false);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            console.log(response)
        }
    });
};

app.updatePackageModuleLimits.init = function() {
    $(this.config.button).on('click', this.ajax.bind(this));
};

$(document).ready(app.updatePackageModuleLimits.init());
