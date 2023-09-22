app.adminProductsUpdate = {};

app.adminProductsUpdate.config = {
    form: '#admin-products-edit-form'
};

app.adminProductsUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

app.adminProductsUpdate.ajax = function(e) {
    $(app.adminProductsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminProductsUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminProductsUpdate.route(),
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(app.adminProductsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

app.adminProductsUpdate.init = function() {
    app.adminProductsUpdate.ajax();
};

$(document).ready(app.adminProductsUpdate.init());