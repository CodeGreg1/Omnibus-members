"use strict";

app.depositCheckout = {};

app.depositCheckout.config = {
    form: '#checkout-form',
    parent: '#deposit-checkout'
};

app.depositCheckout.getAmount = function() {
    return parseFloat($(this.config.parent+ ' [name="amount"]').val() || 0);
};

app.depositCheckout.getCurrency = function() {
    return $(this.config.parent+ ' [name="currency"]').val();
};

app.depositCheckout.getMethodCurrency = function() {
    return $(this.config.parent+ ' #method-currency').val();
};

app.depositCheckout.getFixedCharge = function() {
    return parseFloat($(this.config.parent+ ' #method-fixed-charge').val());
};

app.depositCheckout.getPercentCharge = function() {
    return parseFloat($(this.config.parent+ ' #method-percent-charge').val());
};

app.depositCheckout.getMinLimit = function() {
    return parseFloat($(this.config.parent+ ' #method-min').val());
};

app.depositCheckout.getMaxLimit = function() {
    return parseFloat($(this.config.parent+ ' #method-max').val());
};

app.depositCheckout.initTotal = function() {
    const methodCurrency = this.getMethodCurrency();
    const amntCurrency = this.getCurrency();
    const amount = this.getAmount();
    const percentChargeValue = this.getPercentCharge();
    const fixCharge = parseFloat((app.currency.convert(
        this.getFixedCharge(),
        methodCurrency,
        amntCurrency,
        false
    )).toFixed(2));

    var percentCharge = 0;
    if (percentChargeValue) {
        percentCharge =(percentChargeValue/100) * amount;
    }

    var total = 0;
    if (amount) {
        total = [amount, fixCharge, percentCharge].reduce((partialSum, a) => partialSum + a, 0);
    }

    $(this.config.parent+ ' #checkout-fixed-charge').html(app.currency.format(fixCharge, amntCurrency));
    $(this.config.parent+ ' #checkout-percent-charge').html(app.currency.format(percentCharge, amntCurrency));
    $(this.config.parent+ ' .checkout-total').html(app.currency.format(total, amntCurrency));
    $(this.config.parent+ ' .charges-desc').html(`(${app.currency.format(fixCharge, amntCurrency)} + ${percentChargeValue}%)`);
    $(this.config.parent+ ' .currency-code').html(amntCurrency);
    $(this.config.parent+ ' .fixed-charge-amount-desc').html(app.currency.format(fixCharge, amntCurrency));
};

app.depositCheckout.amntChange = function() {
    $(this.config.parent+ ' [name="amount"]').on('input', function() {
        app.depositCheckout.initTotal();
    });
};

app.depositCheckout.currencyChange = function() {
    $(this.config.parent+ ' [name="currency"]').on('change', function() {
        app.depositCheckout.initTotal();
        app.depositCheckout.initRangeLimits();
    });
};

app.depositCheckout.initRangeLimits = function() {
    const methodCurrency = this.getMethodCurrency();
    const amntCurrency = this.getCurrency();
    $(this.config.parent+ ' .range-min').html(
        app.currency.convert(
            this.getMinLimit(),
            methodCurrency,
            amntCurrency
        )
    );
    $(this.config.parent+ ' .range-max').html(
        app.currency.convert(
            this.getMaxLimit(),
            methodCurrency,
            amntCurrency
        )
    );
};

app.depositCheckout.init = function() {
    this.amntChange();
    this.currencyChange();
};

$(document).ready(app.depositCheckout.init());
