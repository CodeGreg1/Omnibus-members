"use strict";

app.userAffiliate = {};

app.userAffiliate.config = {
    urlClipboardBtn: '.btn-copy-affiliate-code-clipboard'
};

app.userAffiliate.copyUrlToClipboard = function() {
    const self = this;
    $(self.config.urlClipboardBtn).on('click', function() {
        var el = $(this).find('code')[0];
        navigator.clipboard.writeText(el.innerText);
    });
};

app.userAffiliate.init = function() {
    this.copyUrlToClipboard();
};

$(document).ready(app.userAffiliate.init());
