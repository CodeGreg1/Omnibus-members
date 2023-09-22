"use strict";

// define app.modulesCreate object
app.modulesCreate = {};

// handle app.modulesCreate object configuration
app.modulesCreate.config = {
    form: '#modules-create-form',
    route: '/admin/module/create',
    field: {
        type: {
            in_create: 'in_create',
            field_validation: 'field_validation'
        }
    }
};

// handle on module create getting data
app.modulesCreate.data = function() {
    return $(this.config.form).serializeArray();
};

// handle on getting module name with ajax request
app.modulesCreate.name = function() {
    $('[name="module_name"]').on('change', function() {
        var name = $(this).val();
        
        if(name != '') {
            $.ajax({
                type: 'GET',
                url: '/admin/module/name/' + name,
                beforeSend: function () {
                    $('[name="model_name"]').attr('disabled', true)
                    $('[name="menu_title[name]"]').attr('disabled', true)
                },
                success: function (response, textStatus, xhr) {
                    $('[name="module_name"]').val(response.module);
                    $('[name="model_name"]').val(response.model_name);
                    $('[name="menu_title[name]"]').val(response.module);
                }
            });
        }

        $('[name="model_name"]').parents('.form-group').removeClass('error');
        $('[name="model_name"]').parents('.form-group').find('.message').remove();
        $('[name="menu_title[name]"]').parents('.form-group').removeClass('error');
        $('[name="menu_title[name]"]').parents('.form-group').find('.message').remove();

        $('[name="model_name"]').removeAttr('disabled')
        $('[name="menu_title[name]"]').removeAttr('disabled')
         
    });
    
};

// handle on module create selecting type
app.modulesCreate.type = function() {
    $('#type').on('change', function() {

        var type = $(this).val();

        var template = app.modulesFieldExtraOptions.template.get(type);
        var validations = app.modulesValidation.validation.rules(type);

        $('#field-settings-form [name=\"field_validation\"]').html(validations);
        $('#field-extra-options').html(template);

        app.modulesCreate.fieldExtraOptionsSepartor(template);
    });
};

// handle on hide/show extra options separator
app.modulesCreate.fieldExtraOptionsSepartor = function(template) 
{
    if(template != '') {
        $('#field-extra-options-separator').show();
    } else {
        $('#field-extra-options-separator').hide();
    }
}

// handle on checkbox increate
app.modulesCreate.inCreate = function() {
    $(document).delegate('#field-settings-form [name=\"in_create\"]', 'click', function() {
        if(!$(this).is(':checked')) {
            $('#field-settings-form [name=\"field_validation\"]').html('<option value="optional">'+app.trans('Optional')+'</option>');
        } else {
            $('#field-settings-form [name=\"field_validation\"]').html('<option value="optional">'+app.trans('Optional')+'</option><option value="required">'+app.trans('Required')+'</option><option value="required_unique">'+app.trans('Required | Unique')+'</option>');
        }
    });
};

// handle on checkbox soft deletes
app.modulesCreate.softDeletes = function() {
    $('[name="included[soft_deletes]"]').on('click', function() {
        if($(this).is(':checked')) {
            $("#fields-table > tbody:last-child").append(app.modulesFieldGenerator.softDelete());
        } else {
            $('.crud-field').each(function() {
                if($(this).attr('name') == 'fields[deleted_at]') {
                    $(this).parents('tr').remove();
                }
            });
        }
    });
};

// handle on deleting field on settings
app.modulesCreate.deleteField = function() {
    $(document).delegate('.delete-field-settings', 'click', function() {
        $(this).parents('tr').remove();
    });
};

// handle on validating fields
app.modulesCreate.valid = function() {
    var $excludedValidField = ['fields[id]', 'fields[created_at]', 'fields[updated_at]', 'fields[deleted_at]'];
    var $result = [];
    $('.crud-field').each(function() {
        if(!$excludedValidField.includes($(this).attr('name'))) {
            $result.push(true);
        }
    });

    return $result.length>0;
};

// handle on ajax request with checking if the form is valid show a popup modal if the module is for rebuilding
app.modulesCreate.ajax = function(e) {
    e.preventDefault();

    var $self = this;
    var $button = $($self.config.form).find(':submit');
    var $content = $button.html();
    var $data = $self.data();

    // At least one field checking
    if(!app.modulesCreate.valid()) {
        bootbox.alert(app.trans('To continue please add at least one field.'));
        return;
    }

    // Rebuilding module checking
    if($('[name="id"]').length && $('[name="id"]').val() != '') {
        bootbox.confirm({
            title: app.trans('Are you sure?'),
            message: app.trans('Rebuilding this module will remove your custom codes and table records.'),
            buttons: {
                confirm: {
                    label: app.trans('Yes'),
                    className: 'btn-danger'
                },
                cancel: {
                    label: app.trans('No'),
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if ( result ) {
                     app.modulesCreate.ajaxRequest($self, $data, $button, $content);
                }
            }
        });
    } else {
        app.modulesCreate.ajaxRequest($self, $data, $button, $content);
    }
};

// handle on saving/updating module via ajax
app.modulesCreate.ajaxRequest = function($self, $data, $button, $content) {
    $.ajax({
        type: 'POST',
        url: $self.config.route,
        data: $data,
        beforeSend: function () {
            app.buttonLoader($button);
        },
        success: function (response, textStatus, xhr) {
            app.redirect(response.data.redirectTo);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            var response = XMLHttpRequest;
            // Check check if form validation errors
            app.formErrors($self.config.form, response.responseJSON, response.status);
            // Reset button
            app.backButtonContent($button, $content);
        }
    });
};

// handle on intializing font awesomes icons for module
app.modulesCreate.iconPicker = function() {

    if(typeof $.fn.iconpicker !== 'undefined') {
        $('#menu-icon-picker').iconpicker().on('change', function(e) {
            $('[name="menu_title[icon]"]').val(e.icon)
        });
    }
    
};

// handle on parsing fields
app.modulesCreate.fieldParser = function() {
    $('[name="field_visual_title"]').on('keyup', function() {
        var $this = $(this);
        var label = $this.val();
        var fieldName = $('[name="field_database_column"]');
        label = label.replace(/[^a-zA-Z-0-9]/g, ' ');

        var dbField = label.replace(/ /g, '_');

        fieldName.val(dbField.toLowerCase());
        $this.val(label.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        }));

        fieldName.removeAttr('disabled');
        fieldName.parents('.form-group').removeClass('error');
        fieldName.parents('.form-group').find('.message').remove();
         
    });
};

// handle on manipulating field status
app.modulesCreate.fieldStatus = function() 
{
    $(document).delegate('.field-status','click', function() {
        var $that = $(this);
        var type = $that.attr('data-field-type');
        var status = $that.attr('data-field-status');
        var value = $that.attr('data-field-status') == 1 ? 0 : 1;

        if(app.modulesCreate.config.field.type.in_create == type && value == 0) {
            bootbox.confirm({
                title: app.trans('Are you sure?'),
                message: app.trans('Removing this field from create page will not allow to be required.'),
                buttons: {
                    confirm: {
                        label: app.trans('Yes'),
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: app.trans('No'),
                        className: 'btn-default'
                    }
                },
                callback: function (result) {
                    if ( result ) {
                        app.modulesCreate.fieldStatusChange($that, status, type, value);

                        var fieldValidation = $that.parents('tr').find('[data-field-type="field_validation"]');
                        app.modulesCreate.fieldStatusChange(fieldValidation, status, 'field_validation', value);
                    }
                }
            });
        } else if (app.modulesCreate.config.field.type.field_validation == type && value == 1) {
            app.modulesCreate.fieldStatusChange($that, status, type, value);

            var fieldValidation = $that.parents('tr').find('[data-field-type="in_create"]');
            app.modulesCreate.fieldStatusChange(fieldValidation, status, 'in_create', value);
        } else {
            app.modulesCreate.fieldStatusChange($that, status, type, value);
        
        }
        
    })
}

// handle on changing field status
app.modulesCreate.fieldStatusChange = function(selector, status, type, value) {
    if(status == 1) {
        selector.removeClass('fa-check').removeClass('text-success');
        selector.addClass('fa-times').addClass('text-danger');
    } else {
        selector.addClass('fa-check').addClass('text-success');
        selector.removeClass('fa-times').removeClass('text-danger');
    }

    selector.attr('data-field-status', value);
    
    var data = selector.parents('tr').find('.crud-field').val();

    data = JSON.parse(data);

    selector.parents('tr').find('.crud-field').val(window.JSON.stringify(app.modulesFieldUpdateValueByKey.set(data, type, value)));
};

// handle on editing field settings
app.modulesCreate.editFieldSettings = function() {
    $(document).delegate('.edit-field-settings', 'click', function() {
        var $that = $(this);
        var choicesField = ['radio', 'select', 'checkbox'];
        var data = $that.parents('tr').find('.crud-field').val();
        data = JSON.parse(data);
        var fieldType = app.modulesFieldSettingsCreate.getByName(data, 'field_type');
        var fieldId = app.modulesFieldSettingsCreate.getByName(data, 'field_id');
        
        $('#field-settings-form').trigger("reset");
        $('#field-settings-form').prepend('<input type="hidden" name="field_id" value="'+fieldId+'">')

        $.each($('#type option'), function() {
            $(this).removeAttr('selected');
        });

        $.each(data, function() {
            var $that = this;

            if($that.name == 'field_type') {
                
                $.each($('#type option'), function() {
                    if($(this).attr('value') == $that.value) {
                        $(this).attr('selected', 'selected');
                    }
                });

                $('#type').trigger('change');
            } else {

                var $el = $('#field-settings-form ' + '[name="'+$that.name+'"]');

                var $isSelect = $el.is('select');

                var type = ($isSelect) ? 'select' : $el.attr('type');

                switch (type) {
                    case 'checkbox':
                        // reset all checkbox that checked in default
                        $.each($('#field-settings-form ' + '[type="checkbox"]'), function() {
                            $(this).removeAttr('checked');
                        });

                        if($that.value == 'on') {
                            $el.prop('checked', true);
                        }
                        
                        break;
                    case 'select':

                        if(fieldType == 'belongsToManyRelationship' && $that.name == 'related_model') {
                            $('#field-settings-button').prop('disabled', true);

                            var fieldShow = app.modulesFieldSettingsCreate.getByName(data, 'field_show');

                            setTimeout(function() {
                                $el.val($that.value).trigger('change');

                                setTimeout(function() {
                                    $('[name="field_show"]').val(fieldShow).trigger('change');
                                    $('#field-settings-button').prop('disabled', false);
                                }, 1500);

                            }, 1500);
                        } else if(fieldType == 'belongsToManyRelationship' && $that.name == 'field_show') {
                            //skip
                        } else if(fieldType == 'belongsToRelationship' && $that.name == 'related_model') {
                            $('#field-settings-button').prop('disabled', true);
                            var fieldShow = app.modulesFieldSettingsCreate.getByName(data, 'field_show');
                            var additionalFields = app.modulesFieldSettingsCreate.getAdditionalFields(data);

                            setTimeout(function() {
                                $el.val($that.value).trigger('change');

                                setTimeout(function() {
                                    $('[name="field_show"]').val(fieldShow).trigger('change');

                                    $.each($('[name="additional_fields"] > option'), function() {
                                        if(additionalFields.includes($(this).attr('value'))) {
                                            $(this).prop('selected', true);
                                        }
                                    });

                                    $('#field-settings-button').prop('disabled', false);
                                }, 1500);
                            }, 1000);
                        } else {
                            $el.val($that.value);
                        }
                        
                        break;
                    default:
                        if(choicesField.includes(fieldType)) {
                            if($el.length < 1) {
                                
                                var arrName = $that.name.split('[');
                                var numRes = arrName[1];

                                var regex = /\d+/g;
                                var matches = numRes.match(regex);

                                if('choices_database_value' == arrName[0]) {
                                    var htmlInput = '<tr>';
                                    htmlInput += '<td>';
                                    htmlInput += '<input type="text" class="form-control" name="'+arrName[0]+'['+matches[0]+']" value="'+$that.value+'" required data-validation="required|'+app.trans('The database value field is required.')+'">';
                                    htmlInput += '</td>';
                                    htmlInput += '<td>';
                                    htmlInput += '<input type="text" class="form-control" name="choices_label_text['+matches[0]+']" value="'+$that.value+'" required data-validation="required|'+app.trans('The label text field is required.')+'">';
                                    htmlInput += '</td>';
                                    htmlInput += '<td>';
                                    htmlInput += '<button type="button" class="btn btn-danger btn-sm btn-remove-field-choice"><i class="fas fa-trash"></i></button>';
                                    htmlInput += '</td>';
                                    htmlInput += '</tr>';

                                    $('.table-field-choices tbody').append(htmlInput);
                                    $('#field-settings-form ' + '[name="'+$that.name+'"]').val($that.value);
                                }
                            }

                            $el.val($that.value);
                        } else {
                            $el.val($that.value);
                        }
                        
                }

            }
            
        });

    });
};

// handle on event crud types admin/user type module
app.modulesCreate.checkboxCrudTypes = function() {
    $('[name="included[admin]"]').on('click', function() {
        if(!$(this).is(':checked') && !$('[name="included[user]"]').is(":checked")) {
            $(this).prop('checked', true);
        }
    });

    $('[name="included[user]"]').on('click', function() {
        if(!$(this).is(':checked')) {
            $('[name="included[admin]"]').prop('checked', true);
        }
    });
};

// intializing functions that need to load
app.modulesCreate.init = function() {
    $(this.config.form).on('submit', this.ajax.bind(this));

    app.modulesCreate.name();
    app.modulesCreate.type();
    app.modulesCreate.inCreate();
    app.modulesCreate.iconPicker();
    app.modulesCreate.softDeletes();
    app.modulesCreate.deleteField();
    app.modulesCreate.fieldParser();
    app.modulesCreate.fieldStatus();
    app.modulesCreate.editFieldSettings();
    app.modulesCreate.checkboxCrudTypes();
};

// initialize app.modulesCreate object until the document is loaded
$(document).ready(app.modulesCreate.init());