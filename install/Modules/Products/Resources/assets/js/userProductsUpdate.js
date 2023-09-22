app.userProductsUpdate = {};

app.userProductsUpdate.config = {
    form: '#user-products-edit-form'
};

app.userProductsUpdate.route = function(id) {
    return $(this.config.form).data('action');
};

app.userProductsUpdate.ajax = function(e) {
    $(app.userProductsUpdate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.userProductsUpdate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.userProductsUpdate.route(),
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
                app.formErrors(app.userProductsUpdate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 

    });

};

app.userProductsUpdate.init = function() {
    app.userProductsUpdate.ajax();
};

$(document).ready(app.userProductsUpdate.init());