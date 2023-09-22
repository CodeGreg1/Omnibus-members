"use strict";

app.adminAffiliateCommissionTypeUpdate = {};

app.adminAffiliateCommissionTypeUpdate.config = {
    showModalBtn: '.btn-admin-affiliate-setting-edit',
    modal: '#admin-edit-commission-type-modal',
    form: '#admin-edit-commission-type-form',
    btnAddLevel: '.btn-add-commission-type-level',
    btnRemoveLevel: '.btn-remove-affiliate-commission-type-level',
};

app.adminAffiliateCommissionTypeUpdate.route = function() {
    const id = $(this.config.form).find('[name="id"]').val();
    return `/admin/affiliates/commission-types/${id}/update`;
};

app.adminAffiliateCommissionTypeUpdate.data = function() {
    const data = new FormData();
    data.append('status', $(this.config.form).find('[name="active"]:checked').length);

    const conditions = {};
    $(this.config.form).find('.affilliate-commission-type-conditions input').map(function(i, input) {
        conditions[input.name] = input.checked ? 1 : 0;
    });
    data.append(
        'conditions',
        JSON.stringify(conditions)
    );

    const levels = $(this.config.form).find('.affiliate-commission-type-levels tr').map(function(i, tr) {
        return $(tr).find('input.commission-type-level').val();
    }).get();
    data.append(
        'levels',
        JSON.stringify(levels)
    );

    return data;
};

app.adminAffiliateCommissionTypeUpdate.ajax = function() {
    const self = this;
    $(self.config.form).on('submit', function(e) {
        e.preventDefault();

        var $button = $(self.config.form).find(':submit');
        var $content = $button.html();

        $.ajax({
            type: 'POST',
            url: self.route(),
            data: self.data(),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.redirect(response.data.redirectTo);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors(self.config.form, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
};

app.adminAffiliateCommissionTypeUpdate.initEditModal = function() {
    const self = this;
    $(document).delegate(self.config.showModalBtn, 'click', function(e) {
        const json = $(this).closest('tr').data('json');
        $(self.config.form).find('[name="id"]').val(json.id);
        $(self.config.form).find('[name="name"]').val(json.name);
        $(self.config.form).find('[name="active"]').prop('checked', !!json.active);
        if (json.conditions  && (Object.keys(json.conditions)).length) {
            $('.affilliate-commission-type-conditions').append('<h6 class="mb-2">'+app.trans('Conditions')+'</h6>');
            for (var key in json.conditions) {
                var value = json.conditions[key];
                var html = `<div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="${key}" id="${key}" ${value ? 'checked="checked"' : false}>
                            <label class="custom-control-label" for="${key}">${app.adminAffiliateCommistionTypeView.getConditionName(key)}</label>
                        </div>
                    </div>`;
                $('.affilliate-commission-type-conditions').append(html);
            }
        }
        for (let index = 0; index < json.levels.length; index++) {
            const rate = json.levels[index];
            var html = `<tr>
                            <td>${app.trans('Level :level', {level: index+1})}</td>
                            <td>
                                <div class="form-group mb-0">
                                    <input type="text" class="form-control commission-type-level" data-decimals="2" value="${rate}">
                                </div>
                            </td>
                            <td class="text-right">
                                <a href="javascript:void(0)" class="btn btn-icon btn-block btn-danger btn-remove-affiliate-commission-type-level ${json.levels.length === 1 ? 'disabled': ''}" ${json.levels.length === 1 ? 'disabled="disabled"': ''}><i class="fas fa-times"></i></a>
                            </td>
                        </tr>`;
            $(self.config.form).find('.affiliate-commission-type-levels tbody').append(html);
        }
        $(self.config.modal).modal('show');
    });
};

app.adminAffiliateCommissionTypeUpdate.addLevel = function() {
    const self = this;
    $(self.config.btnAddLevel).on('click', function() {
        $(self.config.form).find('.affiliate-commission-type-levels tbody tr a.btn-remove-affiliate-commission-type-level').removeClass('disabled').prop('disabled', false);
        const level = $(self.config.form)
            .find('.affiliate-commission-type-levels tbody tr').length;
        $(self.config.form).find('.affiliate-commission-type-levels tbody').append(`
            <tr>
                <td>${app.trans('Level :level', {level: level+1})}</td>
                <td>
                    <div class="form-group mb-0">
                        <input type="text" class="form-control commission-type-level" data-decimals="2" value="1">
                    </div>
                </td>
                <td class="text-right">
                    <a href="javascript:void(0)" class="btn btn-icon btn-block btn-danger btn-remove-affiliate-commission-type-level"><i class="fas fa-times"></i></a>
                </td>
            </tr>
        `);
    });
};

app.adminAffiliateCommissionTypeUpdate.removeLevel = function() {
    const self = this;
    $(document).delegate(self.config.btnRemoveLevel, 'click', function() {
        if ($(self.config.form).find('.affiliate-commission-type-levels tbody tr').length > 1) {
            $(this).closest('tr').remove();
            $(self.config.form).find('.affiliate-commission-type-levels tbody tr').map(function(i, tr) {
                $(tr).find('td:first-child').html(`${app.trans('Level :level', {level: i+1})}`);
            });
        }

        if ($(self.config.form).find('.affiliate-commission-type-levels tbody tr').length === 1) {
            $(self.config.form).find('.affiliate-commission-type-levels tbody tr a.btn-remove-affiliate-commission-type-level').addClass('disabled').prop('disabled', true);
        }
    });
};

app.adminAffiliateCommissionTypeUpdate.onModalHide = function() {
    const self = this;
    $(this.config.modal).on('hide.bs.modal', function () {
        $(self.config.form).find('[name="name"]').val('');
        $(self.config.form).find('[name="active"]').prop('checked', false);
        $(self.config.form).find('.affilliate-commission-type-conditions').html('');
        $(self.config.form).find('.affiliate-commission-type-levels tbody').html('');
    });
};

app.adminAffiliateCommissionTypeUpdate.init = function() {
    this.initEditModal();
    this.onModalHide();
    this.addLevel();
    this.removeLevel();
    this.ajax();
};

$(document).ready(app.adminAffiliateCommissionTypeUpdate.init());
