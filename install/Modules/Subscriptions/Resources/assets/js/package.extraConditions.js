"use strict";

app.packageExtraCondition = {};

app.packageExtraCondition.config = {
    list: '#package-extra-conditions',
    addNewBtn: '.btn-add-package-extra-condition',
    removeBtn: '.btn-remove-package-extra-condition',
    noList: '#no-package-extra-condition'
};

app.packageExtraCondition.newHtml = function(i) {
    return `<div class="accordion border-top pt-3" id="package-extra-condition-panel-${ i }" data-id="${i}">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-between flex-grow-1 cursor-pointer" role="button" data-toggle="collapse" data-target="#package-extra-condition-${i}" aria-expanded="true">
                        <h6 class="price-title">${ app.trans('Extra condition details') }</h6>
                        <i class="fa fa-angle-down"></i>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger ml-3 btn-remove-package-extra-condition" data-id="${i}">
                        <h6 class="mb-0">&times;</h6>
                    </button>
                </div>

                <div class="accordion-body collapse show px-0" id="package-extra-condition-${i}" data-parent="#package-extra-conditions">
                    <div class="form-group">
                        <label>${ app.trans('Name') }</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>${ app.trans('Description') } <span class="text-muted">(${ app.trans('Optional') })</span></label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>${ app.trans('Shortcode') }</label>
                        <input type="text" name="shortcode" class="form-control">
                    </div>
                    <div class="form-group mb-0">
                        <label>${ app.trans('Value') }</label>
                        <input type="text" name="value" class="form-control">
                    </div>
                </div>
            </div>`;
};

app.packageExtraCondition.addConditionHtml = function() {
    var ids = $(this.config.list).find('.accordion').map(function() {return parseInt(this.dataset.id)}).get();
    var maxId = Math.max(...ids);
    $('.collapse').collapse('hide');
    $(this.config.list).append(this.newHtml(maxId+1));
    $(this.config.noList).addClass('d-none');
};

app.packageExtraCondition.handleRemovePricing = function(e) {
    var button = $(e.target).closest('button');
    $(this.config.list).find('.accordion[data-id="'+button.data('id')+'"]').remove();
    if (!$(this.config.list + ' .accordion').length) {
        $(this.config.noList).removeClass('d-none');
    }
};

app.packageExtraCondition.getData = function() {
    return $(this.config.list).find('.accordion').map(function(i, e) {
        return {
            name: $(e).find('[name="name"]').val(),
            description: $(e).find('[name="description"]').val(),
            shortcode: $(e).find('[name="shortcode"]').val(),
            value: $(e).find('[name="value"]').val()
        };
    }).get();
};

app.packageExtraCondition.init = function() {
    $(this.config.addNewBtn).on('click', this.addConditionHtml.bind(this));
    $(document).delegate(this.config.removeBtn, 'click', this.handleRemovePricing.bind(this));
};

$(document).ready(app.packageExtraCondition.init());
