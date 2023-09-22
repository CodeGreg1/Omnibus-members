app.adminProductsCreate = {};

app.adminProductsCreate.config = {
    form: '#admin-products-create-form',
    route: '/admin/products/create'
};

app.adminProductsCreate.ajax = function() {

    $(app.adminProductsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.adminProductsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.adminProductsCreate.config.route,
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
                app.formErrors(app.adminProductsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

app.adminProductsCreate.init = function() {
    app.adminProductsCreate.ajax();
};

$(document).ready(app.adminProductsCreate.init());