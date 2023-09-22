"use strict";

// define app.adminLanguagesEditDatatable object
app.adminLanguagesEditDatatable = {};

// handle getting ID for languages edit datatable
app.adminLanguagesEditDatatable.getId = function() {
    return $("#languages-edit-datatable").data('id');
};

// handle getting Code for languages edit datatable
app.adminLanguagesEditDatatable.getCode = function() {
    return $("#languages-edit-datatable").data('code');
};

// handle getting route for language edit datatable
app.adminLanguagesEditDatatable.route = function() {
    return '/admin/languages/edit-datatable/' + app.adminLanguagesEditDatatable.getId();
};

// handle manipulating visibility edit status button
app.adminLanguagesEditDatatable.btnEditStatus = function(button) {
    button.parents('form').find('.languages-phrase-txt').hide();
    button.parents('form').find('.languages-phrase-txtbox').show();
    button.parents('form').find('.btn-translate-language-phrase').hide();
    button.parents('form').find('.btn-save-language-phrase').show();
    button.parents('form').find('.btn-cancel-language-phrase').show();
    button.hide();
};

// handle manipulating visibility of cancel status button
app.adminLanguagesEditDatatable.btnCancelStatus = function(button) {
    button.parents('form').find('.languages-phrase-txt').show();
    button.parents('form').find('.btn-edit-language-phrase').show();
    button.parents('form').find('.btn-translate-language-phrase').show();
    button.parents('form').find('.languages-phrase-txtbox').hide();
    button.parents('form').find('.btn-save-language-phrase').hide();
    button.parents('form').find('.btn-cancel-language-phrase').hide();
};

// handle edit language phrase and ajax request
app.adminLanguagesEditDatatable.editLanguagePhrase = function() 
{
    $(document).delegate('.btn-edit-language-phrase', 'click', function() {
        app.adminLanguagesEditDatatable.btnEditStatus($(this));

        $(this).parents('form').addClass('table-form-language-editing');
    });

    $(document).delegate('.btn-cancel-language-phrase', 'click', function() {
        app.adminLanguagesEditDatatable.btnCancelStatus($(this));

        $(this).parents('form').removeClass('table-form-language-editing');
    });

    $(document).delegate('.btn-translate-language-phrase', 'click', function() {

        var $self = this;
        var $button = $(this);
        var $content = $button.html();

        var $formSelector = $button.parents('form').attr('id');
        var $id = $button.parents('form').find('[name="id"]').val();
        var $code = $button.parents('form').find('[name="code"]').val();
        var $key = $button.parents('form').find('[name="key"]').val();
        var $value = $button.parents('form').find('[name="value"]').val();

        $.ajax({
            type: 'POST',
            url: '/admin/languages/translate-phrase',
            data: {
                id: $id,
                code: $code,
                key: $key,
                value: $value
            },
            dataType:"JSON",
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                $button.parents('form').find('.languages-phrase-txt').text(response.data.value);
                $button.parents('form').find('.languages-phrase-txtbox').val(response.data.value);
                app.backButtonContent($button, $content);
                $button.parents('form').removeClass('table-form-language-editing');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors('#' + $formSelector, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
        
    });

    $(document).delegate('.btn-save-language-phrase', 'click', function() {

        var $self = this;
        var $button = $(this);
        var $content = $button.html();

        var $formSelector = $button.parents('form').attr('id');
        var $id = $button.parents('form').find('[name="id"]').val();
        var $key = $button.parents('form').find('[name="key"]').val();
        var $value = $button.parents('form').find('[name="value"]').val();
        var $default = $button.parents('form').find('[name="default"]').val();

        $.ajax({
            type: 'POST',
            url: '/admin/languages/update-phrase',
            data: {
                id: $id,
                key: $key,
                value: $value,
                default: $default
            },
            dataType:"JSON",
            beforeSend: function () {
                app.buttonLoader($button);
            },
            success: function (response, textStatus, xhr) {
                app.notify(response.message);
                $button.parents('form').find('.languages-phrase-txt').text($value);
                app.backButtonContent($button, $content);
                app.adminLanguagesEditDatatable.btnCancelStatus($button);
                $button.parents('form').removeClass('table-form-language-editing');
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                var response = XMLHttpRequest;
                // Check check if form validation errors
                app.formErrors('#' + $formSelector, response.responseJSON, response.status);
                // Reset button
                app.backButtonContent($button, $content);
            }
        });
    });
    

}

// handle language edit datable configuration
app.adminLanguagesEditDatatable.config = {
    datatable: {
        selectable: false,
        src: app.adminLanguagesEditDatatable.route(),
        resourceName: { singular: app.trans("update language"), plural: app.trans("update languages") },
        columns: [
                       
            {
                title: app.trans('Key'),
                key: 'key',
                classes: 'languages-key-column'
            }, 
            {
                title: app.trans('Value'),
                key: 'value',
                classes: 'languages-value-column',
                element: function(row) {

                    var formId = row.key.replace(/[^A-Za-z]/g, '');

                    var html = '<form id="form-lang-'+formId.replace(/ /g, "-")+'">';
                        html += '<span class="languages-phrase-txt">' + row.value + '</span><br>';
                        html += '<input type="hidden" name="id" class="form-control" value="' + app.adminLanguagesEditDatatable.getId() + '">';
                        html += "<input type='hidden' name='key' class='form-control' value='" + row.key.replace(/'/g, "&#39;") + "'>";
                        html += '<input type="hidden" name="default" class="form-control" value="' + row.default + '">';
                        html += "<div class='form-group'><input type='text' name='value' class='form-control languages-phrase-txtbox' value='" + row.value.replace(/'/g, "&#39;") + "'></div>";
                        html += '<button type="button" class="btn btn-default btn-sm btn-edit-language-phrase mr-1"> <i class="fas fa-edit"></i> ' + app.trans('Edit') + '</button>';

                        if(app.adminLanguagesEditDatatable.getCode() != 'en') {
                            html += '<button type="button" class="btn btn-success btn-sm btn-translate-language-phrase mr-1"> <i class="fas fa-language"></i> ' + app.trans('Translate') + '</button>';
                        }
                        
                        html += '<button type="button" class="btn btn-primary btn-sm btn-save-language-phrase mr-1"> <i class="fas fa-save"></i> ' + app.trans('Save') + '</button>';
                        html += '<button type="button" class="btn btn-warning btn-sm btn-cancel-language-phrase"> <i class="fas fa-times"></i> ' + app.trans('Cancel') + '</button>';
                    html += '</form>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans('No language phrases were found!')
        },
        filterTabs: [
            { label: app.trans('Global'), filters: [] },
            { label: app.trans('Auth.php'), filters: [{ key: 'type', value: 'auth' }] },
            { label: app.trans('Pagination.php'), filters: [{ key: 'type', value: 'pagination' }] },
            { label: app.trans('Passwords.php'), filters: [{ key: 'type', value: 'passwords' }] },
            { label: app.trans('Validation.php'), filters: [{ key: 'type', value: 'validation' }] },
        ],
        filterControl: [
            {
                key: 'type',
                title: app.trans('Type'),
                choices: [
                    { label: app.trans('Global'), value: 'global' },
                    { label: app.trans('Auth.php'), value: 'auth' },
                    { label: app.trans('Pagination.php'), value: 'pagination' },
                    { label: app.trans('Passwords.php'), value: 'passwords' },
                    { label: app.trans('Validation.php'), value: 'validation' }
                ],
                shortcut: true,
                allowMultiple: false,
                showClear: false,
                value: ''
            }
        ],
        sortControl: {
            value: 'id_asc',
            options: [
                { value: 'id_asc', label: app.trans('Latest') },
                { value: 'id_desc', label: app.trans('Oldest') }
            ]
        },
        limit: 25
    }
};

// initialize app.adminLanguagesEditDatatable available functions that need to load
app.adminLanguagesEditDatatable.init = function() {
    app.adminLanguagesEditDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.adminLanguagesEditDatatable.tb = $("#languages-edit-datatable").JsDataTable(this.config.datatable);
    }

    // edit local phrase
    app.adminLanguagesEditDatatable.editLanguagePhrase();
};

// initialize app.adminLanguagesEditDatatable object until the document is loaded
$(document).ready(app.adminLanguagesEditDatatable.init());