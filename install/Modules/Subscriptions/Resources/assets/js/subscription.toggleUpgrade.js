app.subscriptionToggleUpgrade = {};

app.subscriptionToggleUpgrade.config = {
    button: '#btn-subscription-upgrade-package',
    package: '#choosePlan'
};

app.subscriptionToggleUpgrade.handlePackageChange = function(e) {
    var id = $(e.target).val();
    $(this.config.button).attr('disabled', id ? false : true).toggleClass('disabled', id ? false : true);
};

app.subscriptionToggleUpgrade.process = function(e) {
    var url = $(this.config.package).val();
    if (url) {
        window.location.href = url;
    }
};

app.subscriptionToggleUpgrade.init=function(){
    $(this.config.button).on('click', this.process.bind(this));
    $(this.config.package).on('change', this.handlePackageChange.bind(this));
};

$(document).ready(app.subscriptionToggleUpgrade.init());
