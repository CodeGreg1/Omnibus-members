"use strict";

app.adminAffiliateCommistionTypeView = {};

app.adminAffiliateCommistionTypeView.view = function() {
    const self = this;
    $(document).delegate('.btn-admin-affiliate-setting-view', 'click', function() {
        var json = $(this).closest('tr').data('json');
        if (json) {
            var message = `<ul class="list-group mb-4">
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="mr-4">${app.trans('Status')}</span>
                                <strong class="${json.active ? 'text-primary': 'text-danger'}">
                                    ${json.active ? 'Enabled' : 'Disabled'}
                                </strong>
                            </div>
                        </li></ul>`;

            message += `<ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <strong>
                                    ${app.trans('Levels')}
                                </strong>
                                <strong>
                                    ${app.trans('Rate')}
                                </strong>
                            </div>
                        </li>`;
            for (let index = 0; index < json.levels.length; index++) {
                const rate = json.levels[index];
                message += `<li class="list-group-item">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="mr-4">${app.trans('Level :level', {level: index+1})}</span>
                            <span>
                                ${rate}%
                            </span>
                        </div>
                    </li>`;
            }
            message += `</ul>`;

            if (json.conditions && (Object.keys(json.conditions)).length) {
                message += `<ul class="list-group mt-4">
                        <li class="list-group-item">
                            <div class="d-flex align-items-start justify-content-between">
                                <strong>
                                    ${app.trans('Conditions')}
                                </strong>
                            </div>
                        </li>`;

                for (var key in json.conditions) {
                    message += `<li class="list-group-item">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="mr-4">${self.getConditionName(key)}</span>
                            <span>
                                ${json.conditions[key] ? 'Enabled' : 'Disabled'}
                            </span>
                        </div>
                    </li>`;
                }

                message += `</ul>`;
            }

            var dialog = bootbox.dialog({
                title: app.trans(':name details', {name: json.name}),
                message: message,
                buttons: {
                    cancel: {
                        label: app.trans("Close"),
                        className: 'btn-danger'
                    }
                }
            });
        }
    });
};

app.adminAffiliateCommistionTypeView.getConditionName = function(str) {
    str = str.replace(/\_/g, ' ');
    return str.charAt(0).toUpperCase() + str.slice(1);
};

app.adminAffiliateCommistionTypeView.init = function() {
    this.view();
};

$(document).ready(app.adminAffiliateCommistionTypeView.init());
