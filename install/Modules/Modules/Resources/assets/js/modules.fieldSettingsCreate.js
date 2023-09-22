"use strict";

// define app.modulesFieldSettingsCreate object
app.modulesFieldSettingsCreate = {};

// handle app.modulesFieldSettingsCreate object configuration
app.modulesFieldSettingsCreate.config = {
    form: '#field-settings-form',
    route: '/admin/module/create'
};

// handle on module create field stting validation
app.modulesFieldSettingsCreate.validator = function() 
{
    var error = [];
    $.each($("#field-settings-form input, #field-settings-form textarea, #field-settings-form select"), function() {
        
        $(this).parents('.form-group').find('.message').remove();
        $(this).parent('td').find('.message').remove();

        // check field with required validation
        if($(this).val() == '' && app.modulesFieldSettingsCreate.validatorGetMessageByKey($(this), 'required') != '') {

            if($(this).parent('td').length) {
                $(this).parents('td').addClass('error');
                $(this).parents('td').append("<p class='message'>"+app.modulesFieldSettingsCreate.validatorGetMessageByKey($(this), 'required')+"</p>");
            } else {
                $(this).parents('.form-group').addClass('error');
                $(this).parents('.form-group').append("<p class='message'>"+app.modulesFieldSettingsCreate.validatorGetMessageByKey($(this), 'required')+"</p>");
            }

            $(this).on('keyup, change', function() {
                $(this).parents('.form-group').removeClass('error');
                $(this).parents('.form-group').find('.message').remove();

                $(this).parent('td').removeClass('error');
                $(this).parent('td').find('.message').remove()
            });

            error.push(1);
            
        }

        // check field with unique validation
        if(app.modulesFieldSettingsCreate.isDatabaseFieldExist($(this).val()) && app.modulesFieldSettingsCreate.validatorGetMessageByKey($(this), 'unique') != '') {

            $(this).parents('.form-group').addClass('error');
            $(this).parents('.form-group').append("<p class='message'>"+app.modulesFieldSettingsCreate.validatorGetMessageByKey($(this), 'unique')+"</p>");

            $(this).on('keyup', function() {
                $(this).parents('.form-group').removeClass('error');
                $(this).parents('.form-group').find('.message').remove();
            });

            error.push(1);
        }
    });

    return error;
};

// handle on getting message by key for create module field setting
app.modulesFieldSettingsCreate.validatorGetMessageByKey = function(selector, type) {
    var result = '';
    $.each(selector.attr('data-validation') !== undefined && selector.attr('data-validation').split(','), function(key, value) {
        var validation = value.split('|');

        if(validation[0] === type) {
            result = validation[1];
        }
    });

    return result;
};

// handle on create module field settings if the database field is exists
app.modulesFieldSettingsCreate.isDatabaseFieldExist = function(fieldValue) 
{
    var result = [];
    var isEditing = $('#field-settings-form [name="field_id"]').length;
    var fieldId = $('#field-settings-form [name="field_id"]').val();

    $('.crud-field').each(function() {
        var data = JSON.parse($(this).val());

        $.each(data, function(key, row) {
            
            if(row.name == 'field_database_column') {
                if(isEditing) {
                    if( row.value == fieldValue && app.modulesFieldSettingsCreate.getByName(data, 'field_id') != fieldId) {
                        result.push(1);
                    }
                } else {
                    if( row.value == fieldValue) {
                        result.push(1);
                    }
                }
                
            }
        });
    });

    return result.length > 0;
};

// handle on getting by name for create module field setting
app.modulesFieldSettingsCreate.getByName = function(settings, name) 
{
    var result = false;
    $.each(settings, function() {
        if(this.name == name) {
            result = this.value;
        }
    });

    return result;
};

// handle on getting additional fields for create module field setting
app.modulesFieldSettingsCreate.getAdditionalFields = function(settings) 
{
    var name = 'additional_fields';
    var result = [];
    $.each(settings, function() {
        if(this.name == name) {
            result.push(this.value)
        }
    });

    return result;
};

// handle on resetting create module field setting
app.modulesFieldSettingsCreate.reset = function() {

    $('#field-settings-form').trigger("reset");

    $('#field-settings-form [name="field_id"]').remove();

    $.each($('#field-settings-form ' + '[type="checkbox"]'), function() {
        $(this).prop('checked', true);
    });

    $('#field-settings-form .error .message').remove();
    $('#field-settings-form .error').removeClass('error');

    $.each($('#type option'), function() {
        $(this).removeAttr('selected');
    });

    $.each($('#type option'), function() {
        if($(this).attr('value') == 'text') {
            $(this).attr('selected', 'selected');
        }
    });

    $('#type').trigger('change');
    
};

// handle on checking if ID is exist on create module field settings
app.modulesFieldSettingsCreate.isIdExist = function(fieldId) 
{
    var result = [];

    $.each($('.crud-field'), function() {
        var data = $(this).val();
        data = JSON.parse(data);

        if(app.modulesFieldSettingsCreate.getByName(data, 'field_id') == fieldId) {
            result.push(1);
        }
    });

    return result.length > 0;
}

// handle on creating module field settings adding new field
app.modulesFieldSettingsCreate.button = function() {
    $('#field-settings-button').on('click', function() {

        // validate if no error
        if(app.modulesFieldSettingsCreate.validator().length < 1) {
            var data = $(app.modulesFieldSettingsCreate.config.form).serializeArray();
            var fieldId = app.modulesFieldSettingsCreate.getByName(data, 'field_id');

            if(fieldId === false) {
                fieldId = Math.random().toString().substr(2, 10) + '_' + app.modulesFieldSettingsCreate.getByName(data, 'field_database_column');
                data.push({name:'field_id', value: fieldId});
            }

            var template = app.modulesFieldGenerator.template(window.JSON.stringify(data));
            var orderByTemplate = app.modulesOrderByGenerator.template(window.JSON.stringify(data));

            // updating
            if(app.modulesFieldSettingsCreate.isIdExist(fieldId)) {
                $.each($('.crud-field'), function() {
                    var fieldData = $(this).val();
                    fieldData = JSON.parse(fieldData);

                    if(app.modulesFieldSettingsCreate.getByName(fieldData, 'field_id') == fieldId) {
                       $(this).parents('tr').before(template);
                       $(this).parents('tr').remove();
                    }
                });
            // creating
            } else {
                $('#fields-table > tbody#fields').append(template);
                
            }
            $('[name=\"order_by_column\"]').append(orderByTemplate);
           

            $('#field-settings-modal').modal('hide');
            // modify the table column styles
            app.modulesFieldSorter.modifyColumnStyles();
            // reset the modal form
            app.modulesFieldSettingsCreate.reset();
        }
        
    });
};

// handle on closing modal for field settings
app.modulesFieldSettingsCreate.close = function() {
    $('#field-settings-modal [data-dismiss="modal"]').on('click', function() {
        app.modulesFieldSettingsCreate.reset();
    });
};

// handle on initializing app.modulesFieldSettingsCreate.button() and app.modulesFieldSettingsCreate.close() functions
app.modulesFieldSettingsCreate.init = function() {
    app.modulesFieldSettingsCreate.button();
    app.modulesFieldSettingsCreate.close();
    
};

// initialize app.modulesFieldSettingsCreate object until the document is loaded
$(document).ready(app.modulesFieldSettingsCreate.init());