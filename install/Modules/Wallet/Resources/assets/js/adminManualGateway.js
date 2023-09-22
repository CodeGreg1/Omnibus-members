"use strict";

// define app.adminManualGateway object
app.adminManualGateway = {};

app.adminManualGateway.config = {
    userDataList: '#admin-manual-gateway-user-data',
    addUserDataRowBtn: '#btn-admin-add-manual-gateway-user-data-row',
    removeuserDataRowBtn: '.btn-remove-admin-manual-gateway-row'
};

app.adminManualGateway.chargeTypeChange = function() {
    const self = this;
    $('[name="charge_type"]').on('change', function() {
        if (this.value === 'fixed') {
            $('.admin-gateway-fixed-charge').removeClass('d-none');
            $('.admin-gateway-percent-charge').addClass('d-none');
        } else if (this.value === 'percent') {
            $('.admin-gateway-fixed-charge').addClass('d-none');
            $('.admin-gateway-percent-charge').removeClass('d-none');
        } else {
            $('.admin-gateway-fixed-charge').removeClass('d-none');
            $('.admin-gateway-percent-charge').removeClass('d-none');
        }
    });
};

// initialize tinymce plugin
app.adminManualGateway.tinyMCE = function() {
    tinymce.init({
        selector: 'textarea#admin-manual-gateway-instructions',
        plugins: ['preview', 'importcss', 'code', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons'],
        mobile: {
            plugins: ['preview', 'importcss', 'searchreplace', 'autolink', 'autosave', 'save', 'directionality', 'visualblocks', 'visualchars', 'fullscreen', 'image', 'link', 'media', 'codesample', 'table', 'charmap', 'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists', 'wordcount', 'help', 'charmap', 'quickbars', 'emoticons']
        },
        menubar: 'file edit view insert format tools table tc',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange formatpainter removeformat | insertfile image media link anchor codesample | a11ycheck ltr rtl',
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        height: 380,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        toolbar_mode: 'sliding',
        spellchecker_ignore_list: ['Ephox', 'Moxiecode'],
        contextmenu: 'link image table',
        a11y_advanced_options: true,
        promotion: false
    });
};

app.adminManualGateway.userDataRowHtml = function(row = {}) {
    return `<div class="row user-data-row" data-id="${ row.id }">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <input name="field_name" class="form-control" placeholder="${ app.trans('Field name') }" value="${ row.field_name || '' }">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <select name="field_type" class="form-control">
                            <option
                                value="input"
                                ${ row.field_type ? (row.field_type === 'input' ? 'selected' : ''):'' }
                            >${app.trans('Input Text')}</option>
                            <option
                                value="textarea"
                                ${ row.field_type ? (row.field_type === 'textarea' ? 'selected' : ''):'' }
                            >${app.trans('Textarea')}</option>
                            <option
                                value="image_upload"
                                ${ row.field_type ? (row.field_type === 'image_upload' ? 'selected' : ''):'' }
                            >${app.trans('Image upload')}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <select name="required" class="form-control">
                            <option
                                value="true"
                            >${app.trans('Required')}</option>
                            <option
                                value="false"
                            >${app.trans('Optional')}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-1 text-right">
                    <a href="javascript:void(0)" class="btn btn-icon btn-block btn-danger btn-remove-admin-manual-gateway-row mb-4" data-id="${ row.id }"><i class="fas fa-times"></i></a>
                </div>
            </div>`;
};

app.adminManualGateway.addUserDataRow = function() {
    const self = this;
    $(this.config.addUserDataRowBtn).on('click', function() {
        $(self.config.userDataList+ ' .no-user-data-row').addClass('d-none');
        var ids = $(self.config.userDataList)
            .find('.user-data-row').map(function() {return parseInt(this.dataset.id)}).get();
        ids = !ids.length ? [1] : ids;
        const id = (Math.max(...ids)) + 1;
        $(self.config.userDataList).append(self.userDataRowHtml({id}));
    });
};

app.adminManualGateway.removeUserDataRow = function() {
    const self = this;
    $(document).delegate(self.config.removeuserDataRowBtn, 'click', function() {
        $(this).parents('.user-data-row').remove();
        if (!$(self.config.userDataList).find('.user-data-row').length) {
            $(self.config.userDataList+ ' .no-user-data-row').removeClass('d-none');
        }
    });
};

app.adminManualGateway.currencyChange = function() {
    $('[name="currency"]').on('change', function() {
        const option = $('[name="currency"]').find('option:selected');
        var currencyHtml = $('.manual-gateway-currency-symbol');
        if (option.length && currencyHtml.length) {
            currencyHtml.html(option.data('symbol'));
        }
    });
};

app.adminManualGateway.init = function() {
    this.chargeTypeChange();
    this.addUserDataRow();
    this.removeUserDataRow();
    this.currencyChange();

    if(typeof tinymce !== 'undefined' && $('#admin-manual-gateway-instructions').length) {
        app.adminManualGateway.tinyMCE();
    }
};

$(document).ready(app.adminManualGateway.init());
