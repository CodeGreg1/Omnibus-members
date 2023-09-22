"use strict";

app.wallet = {};

app.wallet.config = {
    item: '.wallet-item-link'
};

app.wallet.handleItemSelection = function(e) {
    const li = $(e.target).closest('li');
    if (!li.hasClass('active') && !li.find('a.disabled').length) {
        li.siblings('.active').removeClass('active');
        li.addClass('active');
        const currency = app.currency.get(li.data('code'));
        if (currency) {
            const link = $('.wallet-exchange-link');
            $('.wallet-title').html(currency.name+' Wallet');
            $('.wallet-balance').html(app.currency.format(currency.balance, currency.code));
            if (currency.balance) {
                $('.wallet-form .wallet-balance').removeClass('text-danger');
                $('.wallet-form .wallet-balance').addClass('text-success');
                $('.btn-send-money-show-modal').removeClass('disabled').attr('disabled', false);
                link.removeClass('disabled').attr('disabled', false);
            } else {
                $('.wallet-form .wallet-balance').addClass('text-danger');
                $('.wallet-form .wallet-balance').removeClass('text-success');
                $('.btn-send-money-show-modal').addClass('disabled').attr('disabled', true);
                link.addClass('disabled').attr('disabled', true);
            }
            $('.wallet-code').html(currency.code);
            $('.wallet-form [name="from_currency"]').val(currency.code);
            $('.wallet-form [name="currency"]').val(currency.code);

            $('#wallet-exchange-form .wallet-exchange-fixed-amount').html(currency.exhange_charge.fixed_charge_display);
            $('#wallet-exchange-form .wallet-exchange-rate').html(currency.exhange_charge.fixed_charge_rate);

            $('#wallet-send-money-form .wallet-exchange-fixed-amount').html(currency.send_charge.fixed_charge_display);
            $('#wallet-send-money-form .wallet-exchange-rate').html(currency.send_charge.fixed_charge_rate);

            link.attr('href', link.data('href') + '?from='+currency.code);
        }
    }
};

app.wallet.initNumberTextbox = function() {
    $('[data-decimals]').keypress(function(event) {
        const decimals = parseInt(event.target.dataset.decimals) || 2;
        // if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
        //         $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        //     event.preventDefault();
        // }
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) &&
            ((event.which < 48 || event.which > 57) &&
            (event.which != 0 && event.which != 8))) {
            event.preventDefault();
        }

        var text = $(this).val();

        if ((text.indexOf('.') != -1) &&
            (text.substring(text.indexOf('.')).length > decimals) &&
            (event.which != 0 && event.which != 8) &&
            ($(this)[0].selectionStart >= text.length - decimals)) {
            event.preventDefault();
        }
    }).on('paste', function(event) {
        event.preventDefault();
    });
};

app.wallet.init = function() {
    $(this.config.item).on('click', this.handleItemSelection.bind(this));

    this.initNumberTextbox();
};

$(document).ready(app.wallet.init());
