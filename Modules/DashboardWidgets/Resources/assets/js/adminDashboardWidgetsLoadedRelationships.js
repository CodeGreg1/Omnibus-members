"use strict";

// define app.adminDashboardWidgetsLoadedRelationships object
app.adminDashboardWidgetsLoadedRelationships = {};

// handle to load the relationship name for dashboard widgets
app.adminDashboardWidgetsLoadedRelationships.loadRelationshipName = function(val) 
{
    $.each($('#fields-table-to-show .field-to-show-relationship-name'), function() {
        var select = $(this);
        var options = '<option value="">'+app.trans('Select Relationship Name')+'</option>';

        $.each(val.split(','), function(key, value) {
            if(value != '') {
                options += '<option value="'+value+'">'+value+'</option>';
            }
        });

        $(this).html(options);
    });
};

// handle to load the relationship model on select element
app.adminDashboardWidgetsLoadedRelationships.loadRelationshipModel = function() 
{
    $.each($('#fields-table-to-show .field-to-show-relationship-model'), function() {
        var select = $(this);
        var options = '<option value="">'+app.trans('Select Model')+'</option>';

        $.each($('[name="data_source_model"] > option'), function() {
            if($(this).attr('value') != '') {
                options += '<option value="'+$(this).attr('value')+'">'+$(this).html()+'</option>';
            }
        });

        $(this).html(options);
    });
};

// handle loading default option for relationship field options
app.adminDashboardWidgetsLoadedRelationships.changeRelationshipFields = function() {
    $.each($('#fields-table-to-show .field-to-show-relationship-fields'), function() {
        var select = $(this);
        var options = '<option value="">'+app.trans('Select Relationship Fields')+'</option>';

        $(this).html(options);
    });
};

// handle resetting relationship field options
app.adminDashboardWidgetsLoadedRelationships.resetRelationshipFieldsOption = function() {
    var fieldToShowFieldRelationshipClasses = [
        '.field-to-show-relationship-name',
        '.field-to-show-relationship-model',
        '.field-to-show-relationship-fields'
    ];

    $.each(fieldToShowFieldRelationshipClasses, function(key, value) {
        $.each($('#fields-table-to-show ' + value), function() {
            var select = $(this);
            var options = '<option value="">'+app.trans('Add Loaded Relationships')+'</option>';

            $(this).html(options);
            $(this).prop('disabled', true);
        });
    });

};

// initialize dashboard widget relationship with value on change
app.adminDashboardWidgetsLoadedRelationships.init = function() {
    $('[name="loaded_relationships"]').on('change', function() {
    	var val = $(this).val();

        if(val != '') {
            app.adminDashboardWidgetsLoadedRelationships.loadRelationshipName(val);
            app.adminDashboardWidgetsLoadedRelationships.loadRelationshipModel();
            app.adminDashboardWidgetsLoadedRelationships.changeRelationshipFields();
            app.adminDashboardWidgetsFieldToShow.isFieldChecked();
        } else {
            app.adminDashboardWidgetsLoadedRelationships.resetRelationshipFieldsOption();
        }
    });
};

// initialize app.adminDashboardWidgetsLoadedRelationships object until the document is loaded
$(document).ready(app.adminDashboardWidgetsLoadedRelationships.init());