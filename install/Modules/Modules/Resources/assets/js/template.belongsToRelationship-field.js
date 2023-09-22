"use strict";

// handle on generating belongsToRelationship option template
app.modulesFieldExtraOptions.template.belongsToRelationship = function() {

    app.modulesFieldExtraOptions.template.belongsToRelationship.model();
    
    var models = $('[name=\"models\"]').val();
	var html = '';
    html += '<h4>'+app.trans('Extra options')+'</h4>';
    html += '<div class="row">';

        // Select table
        html += '<div class="col-4 col-md-4">';
            html += '<div class="form-group">';
                html += '<label for="model">'+app.trans('Model')+'</label>';
                html += '<select class="form-control" name="related_model" required data-validation="required|'+app.trans('The model field is required.')+'">';
                html += '<option value="">'+app.trans('Select model')+'</option>';
                $.each(JSON.parse(models), function(key, data) {
                    $.each(data, function(table, model) {
                        html += '<option value="'+model.namespace+'">'+model.model_name+'</option>';
                    });
                });
                html += '</select>';
            html += '</div>';
        html += '</div>';

        // Select field to show
        html += '<div class="col-4 col-md-4 belongs-to-options-wrapper">';
            html += '<div class="form-group">';
                html += '<label for="field_show_in_list">'+app.trans('Field to show in list')+'</label>';
                html += '<select class="form-control" name="field_show" required data-validation="required|'+app.trans('The field to show in is required.')+'">';

                html += '</select>';
            html += '</div>';
        html += '</div>';  

        // Select additional field to show
        html += '<div class="col-4 col-md-4 belongs-to-options-wrapper">';
            html += '<div class="form-group">';
                html += '<label for="additional_fields">'+app.trans('Additional fields')+'</label>';
                html += '<select class="form-control" name="additional_fields" multiple>';

                html += '</select>';
            html += '</div>';
        html += '</div>';   


    html += '</div>';

    return html;
};


// NOTE: The code below is use by belongsToManyRelationship
app.modulesFieldExtraOptions.template.belongsToRelationship.model = function() 
{
    // $('[name="related_model"]').prop('disabled');

    // $.ajax({
    //     type: 'GET',
    //     url: '/module/get-models',
    //     contentType: false,
    //     cache: false,
    //     processData: false,
    //     success: function (response, textStatus, xhr) {
    //         var html = '<option value="">Select model</option>';
    //         $.each(response, function(key, data) {
    //             $.each(data, function(table, model) {
    //                 html += '<option value="'+model.namespace+'">'+model.model_name+'</option>';
    //             });
                
    //         });
    //         $('[name="related_model"]').html(html);
    //     }
    // });

    // $('[name="related_model"]').removeAttr('disabled');

    app.modulesFieldExtraOptions.template.belongsToRelationship.columns();
};

// handle on getting table columns via ajax request for belongsToRelationship
app.modulesFieldExtraOptions.template.belongsToRelationship.columnsAjax = function(table) {
    $.ajax({
        type: 'GET',
        url: '/admin/module/table-columns',
        data: {table:table},
        contentType: false,
        cache: false,
        processData: true,
        beforeSend: function() {
            $('#field-settings-button').prop('disabled', true);
        },
        success: function (response, textStatus, xhr) {
            var html = '';
            var cntr = 0;
            var selected;

            html += '<option value="">'+app.trans('Select field to show')+'</option>';

            $.each(response, function(key, data) {
                if(cntr==0) {
                    selected = key;
                }

                html += '<option value="'+key+'">'+data+'</option>';

                cntr++;
            });

            app.modulesFieldExtraOptions.template.belongsToRelationship.hideSelected(selected);

            $('[name="field_show"]').html(html);
            $('[name="additional_fields"]').html(html);

            if(response) {
                $('.belongs-to-options-wrapper').show();
            } else {
                $('.belongs-to-options-wrapper').hide();
            }

            $('#field-settings-button').prop('disabled', false);
            
        },
        error: function() {
            app.modulesFieldExtraOptions.template.belongsToRelationship.columnsAjax(table);
        }
    }); 
};

// handle on getting belongsToRelation columns with ajax request
app.modulesFieldExtraOptions.template.belongsToRelationship.columns = function() 
{
    $(document).delegate('[name="related_model"]', 'change', function(e) {
        e.stopImmediatePropagation();

        var table = $(this).val();

        app.modulesFieldExtraOptions.template.belongsToRelationship.columnsAjax(table);

        $('.belongs-to-options-wrapper').hide();

    });

    app.modulesFieldExtraOptions.template.belongsToRelationship.hide();
    
};

// handle on hiding belongsToRelationship additional fields
app.modulesFieldExtraOptions.template.belongsToRelationship.hide = function() 
{
    $(document).delegate('[name="field_show"]', 'change', function() {
        app.modulesFieldExtraOptions.template.belongsToRelationship.hideSelected($(this).val());
    });
};

// handle on hide/show additional fields
app.modulesFieldExtraOptions.template.belongsToRelationship.hideSelected = function(key) 
{
    setTimeout(function() {
        $('[name="additional_fields"] > option').each(function() {
            if($(this).attr('value') == key) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    }, 100);
};