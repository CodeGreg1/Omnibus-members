"use strict";

app.reorderPricingTable = {};

app.reorderPricingTable.sort = function() {
    $("#pricing-table-packages tbody").sortable({
        delay: 150,
        handle: '.drag-handle',
        cursor: 'move',
        placeholder: 'placeholder'
    });
};

app.reorderPricingTable.init = function() {
    if(typeof $.fn.sortable !== 'undefined') {
        app.reorderPricingTable.sort();
    }
};

$(document).ready(app.reorderPricingTable.init());
