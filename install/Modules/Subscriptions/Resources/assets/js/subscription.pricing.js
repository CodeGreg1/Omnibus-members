app.subscriptionPricing = {};

app.subscriptionPricing.init = function() {
    $('[name="pricing_term"]').on('change', function() {
        var term = $('[name="pricing_term"]:checked').val();
        $('.pricing-terms').addClass('d-none');
        $('#term-'+term).removeClass('d-none');
    });
};

$(document).delegate(app.subscriptionPricing.init());
