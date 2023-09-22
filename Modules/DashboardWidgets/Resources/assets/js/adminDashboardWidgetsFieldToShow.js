"use strict";

// define app.adminDashboardWidgetsFieldToShow object
app.adminDashboardWidgetsFieldToShow = {};

// handle app.adminDashboardWidgetsFieldToShow object configuration
app.adminDashboardWidgetsFieldToShow.checkboxFieldToShow = function(element) {
    var loadedRelationshipValue = $('[name="loaded_relationships"]').val();

    var fieldToShowFieldClasses = [
        '.field-to-show-display',
        '.field-to-show-format-type',
        '.field-to-show-display-format',
        '.field-to-show-width'
    ];

    var fieldToShowFieldRelationshipClasses = [
        '.field-to-show-relationship-name',
        '.field-to-show-relationship-model',
        '.field-to-show-relationship-fields'
    ];

    if(loadedRelationshipValue != '') {
        fieldToShowFieldClasses = fieldToShowFieldClasses.concat(fieldToShowFieldRelationshipClasses);
    }

    var checkbox = element;

    $.each(fieldToShowFieldClasses, function(keyClass, valueClass) {
        if(checkbox.is(':checked')) {
            if(valueClass == '.field-to-show-format-type') {
                checkbox.parents('.row-field-to-show')
                    .find(valueClass)
                    .prop('disabled', false);
            } else {
                checkbox.parents('.row-field-to-show')
                    .find(valueClass)
                    .prop('disabled', false);
            }
        } else {
            checkbox.parents('.row-field-to-show')
                .find(valueClass)
                .prop('disabled', true);
        }
    });
};

// handle app.adminDashboardWidgetsFieldToShow select2 initialization
app.adminDashboardWidgetsFieldToShow.initSelect2 = function() {
    if(typeof $.fn.select2 !== 'undefined') {
        $('.field-to-show-table-wrapper .select2').select2();
        app.adminDashboardWidgetsFieldToShow.loadMultipleSelect2();
    }
};

// handle click event for selecting what field to show using checkbox
app.adminDashboardWidgetsFieldToShow.clickCheckboxFieldToShow = function() {
    $(document).delegate('.checkbox-field-to-show', 'click', function() {
        app.adminDashboardWidgetsFieldToShow.checkboxFieldToShow($(this));
    });
};

// handle checking the field if checked
app.adminDashboardWidgetsFieldToShow.isFieldChecked = function() {
    $.each($('.checkbox-field-to-show'), function() {
        app.adminDashboardWidgetsFieldToShow.checkboxFieldToShow($(this));
    });
};

// handle app.adminDashboardWidgetsFieldToShow object with initializing multiple select2
app.adminDashboardWidgetsFieldToShow.loadMultipleSelect2 = function() {
    $('.row-field-to-show .field-to-show-relationship-fields:not(.select2-hidden-accessible)').select2({
        placeholder: 'Select Relationship Fields'
    });
};

// handle ajax request with field to show of relionship model selected
app.adminDashboardWidgetsFieldToShow.relationshipModel = function() {
    $(document).delegate('.field-to-show-relationship-model', 'change', function(e) {
        var that = $(this);
        var table = that.val();

        $.ajax({
            type: 'GET',
            url: '/admin/dashboard-widgets/table-columns',
            data: {table:table},
            contentType: false,
            cache: false,
            processData: true,
            beforeSend: function() {
                that.parents('.row-field-to-show').find('.select2-selection--multiple .select2-search--inline .select2-search__field').attr('placeholder','Loading...');
            },
            success: function (response, textStatus, xhr) {
                var options = '';
                $.each(response, function(column, option) {

                    if(!app.adminDashboardWidgetsCreate.config.excludedColumns.includes(column)) {
                        options += '<option value="'+column+'">'+column+'</option>';
                    }
                });

                that.parents('.row-field-to-show').find('.field-to-show-relationship-fields').html(options);
            },
            error: function() {
                
            }
        });
    });
};

// handle with hide and show functionality for table wrapper
app.adminDashboardWidgetsFieldToShow.hideShowTableWrapper = function(wrapperClass, showHide) {
    if(showHide == 'show') {
        $(wrapperClass).show();
    } else {
        $(wrapperClass).hide();
    }
};

// handle generating table template
app.adminDashboardWidgetsFieldToShow.template = function(column, option) {
    
    var relationshipNames = $('[name="loaded_relationships"]').val();
    var html = '';

	html += '<tr class="row-field-to-show '+column+'-field-row">';
	
	html += '<td>';
        html += '<div class="custom-control custom-checkbox">';
            html += '<input type="checkbox" name="checkbox_field" class="custom-control-input checkbox-field-to-show" id="custom-check-'+column+'">';
            html += '<label class="custom-control-label" for="custom-check-'+column+'"></label>';
        html += '</div>';
    html += '</td>';
    
    html += '<td>'+column+'</td>';

    html += '<td>';
	    html += '<input disabled type="text" value="'+option['display']+'" name="fields['+column+'][column_name]" class="form-control field-to-show-display">';
	html += '</td>';

    html += '<td>';
        html += '<select disabled class="form-control select2 field-to-show-format-type" name="fields['+column+'][format_type]">';
            html += '<option value="html">'+app.trans('HTML')+'</option>';
            html += '<option value="timeago">'+app.trans('Timeago')+'</option>';
            html += '<option value="date">'+app.trans('Date')+'</option>';
            html += '<option value="datetime">'+app.trans('DateTime')+'</option>';
        html += '</select>';
    html += '</td>';

    html += '<td>';
		html += '<textarea disabled name="fields['+column+'][display_format]" class="form-control field-to-show-display-format">$'+column+'$</textarea>';
	html += '</td>';

	html += '<td>';
        html += '<input disabled type="number" maxlength="100" placeholder="'+app.trans('Column width in percentage.')+'" name="fields['+column+'][width]" class="form-control field-to-show-width">';
    html += '</td>';

	html += '<td>';
        html += '<select disabled class="field-to-show-relationship-name form-control select2" name="fields['+column+'][relationship_name]">';
            if(relationshipNames != '') {
                html += '<option value="">'+app.trans('Select Relationship Name')+'</option>';
                $.each(relationshipNames.split(','), function(key, value) {
                    html += '<option value="'+value+'">'+value+'</option>';
                });
            } else {
                html += '<option value="">'+app.trans('Add Loaded Relationships')+'</option>';
            }
        html += '</select>';
    html += '</td>';

    html += '<td>';
        html += '<select disabled class="field-to-show-relationship-model form-control select2" name="fields['+column+'][relationship_model]">';
            if(relationshipNames != '') {
                html += '<option value="">'+app.trans('Select Model')+'</option>';
            
                $.each($('[name="data_source_model"] > option'), function() {
                    if($(this).attr('value') != '') {
                        html += '<option value="'+$(this).attr('value')+'">'+$(this).html()+'</option>';
                    }
                });
            } else {
                html += '<option value="">'+app.trans('Add Loaded Relationships')+'</option>';
            }
        html += '</select>';
    html += '</td>';

    html += '<td>';
        html += '<select disabled multiple class="field-to-show-relationship-fields form-control" name="fields['+column+'][relationship_fields][]">';
            if(relationshipNames != '') {
                html += '<option value="">'+app.trans('Select Relationship Fields')+'</option>';
            } else {
                html += '<option value="">'+app.trans('Add Loaded Relationships')+'</option>';
            }
        html += '</select>';
    html += '</td>';

	html += '</tr>';

	return html;
};

// handle initializing function that should loaded for app.adminDashboardWidgetsFieldToShow object
app.adminDashboardWidgetsFieldToShow.init = function() {
    app.adminDashboardWidgetsFieldToShow.clickCheckboxFieldToShow();
    app.adminDashboardWidgetsFieldToShow.relationshipModel();
};

// initialize app.adminDashboardWidgetsFieldToShow object until the document is loaded
$(document).ready(app.adminDashboardWidgetsFieldToShow.init());