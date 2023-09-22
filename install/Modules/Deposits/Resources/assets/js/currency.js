"user";

app.currency = {};

app.currency.convert = function(amount, from, to, formatted = true) {
    // Get exchange rates
    const from_rate = this.getCurrencyProp(from, 'exchange_rate', 1);
    const to_rate = this.getCurrencyProp(to, 'exchange_rate', 1);

    if (to_rate === null) {
        return null;
    }

    var value = amount;

    if (from !== to) {
        value = (amount * to_rate) / from_rate;
    }

    // Should the result be formatted?
    if (formatted) {
        return this.format(value, to);
    }

    // Return value
    return value;
};

app.currency.format = function(value, code) {
    value = app.Settlement.floatval(value);
    value = app.Settlement.preg_replace('/[\s\',!]/', '', value.toString());
    var format = this.getCurrencyProp(code, 'format');
    valRegex = '/([0-9].*|)[0-9]/';
    var valFormat = format.match(/([0-9].*|)[0-9]/g);
    valFormat = valFormat[0] || 0;
    const separators = [...valFormat.matchAll(/[\s\',.!]/gi)];
    if (thousand = separators[0] ? separators[0][0] : ',') {
        if (thousand == '!') {
            thousand = '';
        }
    }

    decimal = separators[1] ? separators[1][0] : null;
    decimals = decimal ? app.Settlement.strrchr(valFormat, decimal).substring(1).length : 0;

    if (negative = value < 0 ? '-' : '') {
        value = value * -1;
    }
    value = app.Settlement.number_format(value, decimals, decimal, thousand);
    value = app.Settlement.preg_replace(valRegex, value, format);
    return negative+value;
};

app.currency.numberFormat = function(number, decimals = 0, decPoint = '.', thousandsSep = '') {
    negation = number < 0 ? -1 : 1;
    coefficient = 10 ** decimals;
    number = negation * Math.floor((string) (Math.abs(number) * coefficient)) / coefficient;
    return app.Settlement.number_format(number, decimals, decPoint, thousandsSep);
};

app.currency.getCurrencyProp = function(code, key, d = null) {
    if (!this.hasCurrency(code)) {
        return d;
    }

    const currency = this.get(code);
    return currency[key] || d;
};

app.currency.get = function(code) {
    return this.getCurrencies().find(currency => currency.code === code.toUpperCase());
};

app.currency.getCurrencies = function() {
    return window.currencies || [];
};

app.currency.hasCurrency = function(code) {
    return this.getCurrencies().find(currency => currency.code === code.toUpperCase()) ? true : false;
};
