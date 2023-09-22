app.packageModuleLimitAction = {};

app.packageModuleLimitAction.config = {
    moduleSelectAllCheck: '.package-permission-module-select-all',
    moduleSelectCheck: '.package-permission-module-select',
    moduleInpitLimitAll: '.package-permission-module-limit-all',
};

app.packageModuleLimitAction.toggleInputMode = function(checkbox) {
    var tr = $(checkbox).parents('tr');
    var input = tr.find('.package-permission-limit');
    var select = tr.find('.package-permission-term');
    if (!checkbox.checked) {
        input.val('');
        select.val('month');
    } else {
        var limit = $(app.packageModuleLimitAction.config.moduleInpitLimitAll).val();
        input.val(limit);
    }
    input.attr('disabled', !checkbox.checked);
    select.attr('disabled', !checkbox.checked);
};

app.packageModuleLimitAction.toggleSelectAllPermission = function(e) {
    var module = e.target.dataset.moduleName;
    $(this.config.moduleSelectCheck+'[data-module="'+module+'"]').map(function(i, item) {
        item.checked = e.target.checked;
        app.packageModuleLimitAction.toggleInputMode(item);
    });
};

app.packageModuleLimitAction.toggleSelectPermission = function(e) {
    var module = e.target.dataset.module;
    var checkCount = $(this.config.moduleSelectCheck+'[data-module="'+module+'"]:checked').length;
    var total = $(this.config.moduleSelectCheck+'[data-module="'+module+'"]').length;
    $(this.config.moduleSelectAllCheck+'[data-module-name="'+module+'"]')[0].checked = checkCount === total;
    app.packageModuleLimitAction.toggleInputMode(e.target);
};

app.packageModuleLimitAction.inptutModuleLimitAll = function(e) {
    var limit = e.target.value;
    $(this.config.moduleSelectCheck+':checked').map(function(i, item) {
        $(item).parents('tr').find('.package-permission-limit').val(limit);
    });
};

app.packageModuleLimitAction.init = function() {
    $(document).delegate(this.config.moduleSelectAllCheck, 'change', this.toggleSelectAllPermission.bind(this));
    $(document).delegate(this.config.moduleSelectCheck, 'change', this.toggleSelectPermission.bind(this));
    $(document).delegate(this.config.moduleInpitLimitAll, 'input', this.inptutModuleLimitAll.bind(this));
};

$(document).ready(app.packageModuleLimitAction.init());
