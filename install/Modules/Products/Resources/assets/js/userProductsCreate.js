app.userProductsCreate = {};

app.userProductsCreate.config = {
    form: '#user-products-create-form',
    route: '/user/products/create'
};

app.userProductsCreate.ajax = function() {

    $(app.userProductsCreate.config.form).on('submit', function(e) {

        e.preventDefault();

        var $self = this;
        var $button = $(app.userProductsCreate.config.form).find(':submit');
        var $content = $button.html();

        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            url: app.userProductsCreate.config.route,
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
                app.formErrors(app.userProductsCreate.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        }); 


    });
    
};

app.userProductsCreate.init = function() {
    app.userProductsCreate.ajax();
};

$(document).ready(app.userProductsCreate.init());