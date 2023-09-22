"use strict";

// define app.modulesEditLanguageDatatable object
app.modulesEditLanguageDatatable = {};

// handle on getting ID for module edit language datatable
app.modulesEditLanguageDatatable.getId = function() {
    return $("#module-edit-language-datatable").data('id');
};

// handle on getting code
app.modulesEditLanguageDatatable.getCode = function() {
    return $("#module-edit-language-datatable").data('code');
};

// handle on manipulating route
app.modulesEditLanguageDatatable.route = function() {
    return '/admin/module/edit-language-datatable/' + app.modulesEditLanguageDatatable.getId() + '/' + app.modulesEditLanguageDatatable.getCode();
};

// handle on button edit status hide/show functionality
app.modulesEditLanguageDatatable.btnEditStatus = function(button) {
    button.parents('form').find('.module-phrase-txt').hide();
    button.parents('form').find('.module-phrase-txtbox').show();
    button.parents('form').find('.btn-translate-module-lang-phrase').hide();
    button.parents('form').find('.btn-save-module-lang-phrase').show();
    button.parents('form').find('.btn-cancel-module-lang-phrase').show();
    button.hide();
};

// handle on button cancel status hide/show functionality
app.modulesEditLanguageDatatable.btnCancelStatus = function(button) {
    button.parents('form').find('.module-phrase-txt').show();
    button.parents('form').find('.btn-edit-module-lang-phrase').show();
    button.parents('form').find('.btn-translate-module-lang-phrase').show();
    button.parents('form').find('.module-phrase-txtbox').hide();
    button.parents('form').find('.btn-save-module-lang-phrase').hide();
    button.parents('form').find('.btn-cancel-module-lang-phrase').hide();
};

// handle on editing module phrase with ajax request
app.modulesEditLanguageDatatable.editModulePhrase = function() 
{
    $(document).delegate('.btn-edit-module-lang-phrase', 'click', function() {
        app.modulesEditLanguageDatatable.btnEditStatus($(this));

        $(this).parents('form').addClass('table-form-language-editing');
    });

    $(document).delegate('.btn-cancel-module-lang-phrase', 'click', function() {
        app.modulesEditLanguageDatatable.btnCancelStatus($(this));

        $(this).parents('form').removeClass('table-form-language-editing');
    });

    $(document).delegate('.btn-translate-module-lang-phrase', 'click', function() {

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
            url: '/admin/module/translate-module-language',
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
                $button.parents('form').find('.module-phrase-txt').text(response.data.value);
                $button.parents('form').find('[name="value"]').val(response.data.value);
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

    // handle on saving module language phrase
    $(document).delegate('.btn-save-module-lang-phrase', 'click', function() {

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
            url: '/admin/module/update-module-language',
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
                $button.parents('form').find('.module-phrase-txt').text($value);
                app.backButtonContent($button, $content);
                app.modulesEditLanguageDatatable.btnCancelStatus($button);
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

// handle on module edit language datatable configuration
app.modulesEditLanguageDatatable.config = {
    datatable: {
        src: app.modulesEditLanguageDatatable.route(),
        resourceName: { singular: app.trans('module language'), plural: app.trans('module languages') },
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
                        html += '<span class="module-phrase-txt">' + row.value + '</span><br>';
                        html += '<input type="hidden" name="id" class="form-control" value="' + app.modulesEditLanguageDatatable.getId() + '">';
                        html += '<input type="hidden" name="code" class="form-control" value="' + app.modulesEditLanguageDatatable.getCode() + '">';
                        html += "<input type='hidden' name='key' class='form-control' value='" + row.key.replace(/'/g, "&#39;") + "'>";
                        html += "<div class='form-group'><input type='text' name='value' class='form-control module-phrase-txtbox' value='" + row.value.replace(/'/g, "&#39;") + "'></div>";
                        html += '<button type="button" class="btn btn-default btn-sm btn-edit-module-lang-phrase mr-1"> <i class="fas fa-edit"></i> ' + app.trans('Edit') + '</button>';

                        if(app.modulesEditLanguageDatatable.getCode() != 'en') {
                            html += '<button type="button" class="btn btn-success btn-sm btn-translate-module-lang-phrase mr-1"> <i class="fas fa-language"></i> ' + app.trans('Translate') + '</button>';
                        }
                        
                        html += '<button type="button" class="btn btn-primary btn-sm btn-save-module-lang-phrase mr-1"> <i class="fas fa-save"></i> ' + app.trans('Save') + '</button>';
                        html += '<button type="button" class="btn btn-warning btn-sm btn-cancel-module-lang-phrase"> <i class="fas fa-times"></i> ' + app.trans('Cancel') + '</button>';
                    html += '</form>';

                    return $(html);
                }
            }
        ],
        language: {
            sortTitle: app.trans('Sort'),
            noResultsFoundTitle: app.trans("No module languages were found!")
        },
        filterTabs: [
            { label: app.trans('All'), filters: [] }
        ],
        sortControl: {
            value: 'id_asc',
            options: [
                { value: 'id_asc', label: app.trans('Oldest') },
                { value: 'id_desc', label: app.trans('Latest') }
            ]
        },
        limit: 25
    }
};

// handle on initializing datatable, editModulePhrase() functions
app.modulesEditLanguageDatatable.init = function() {
    app.modulesEditLanguageDatatable.tb =  {};
    if(typeof $.fn.JsDataTable !== 'undefined') {
        app.modulesEditLanguageDatatable.tb = $("#module-edit-language-datatable").JsDataTable(this.config.datatable);
    }

    // edit local phrase
    app.modulesEditLanguageDatatable.editModulePhrase();
};

// initialize app.modulesEditLanguageDatatable object until the document is loaded
$(document).ready(app.modulesEditLanguageDatatable.init());