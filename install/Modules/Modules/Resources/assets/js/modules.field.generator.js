"use strict";

// define app.modulesFieldGenerator object
app.modulesFieldGenerator = {};

// handle app.modulesFieldGenerator object visibility function
app.modulesFieldGenerator.visibility = function(data, field) {
	var result = app.modulesHelper.getData(data, field);

	if(result == 'on') {
		return '<i class="fas fa-check text-success text-bold field-status" data-field-type="'+field+'" data-field-status="1"></i>';
	}

	return '<i class="fas fa-times text-danger text-bold field-status" data-field-type="'+field+'" data-field-status="0"></i>';
};

// handle app.modulesFieldGenerator object rule functionality
app.modulesFieldGenerator.rule = function(data, field) {
	var result = app.modulesHelper.getData(data, field);

	if(result != 'optional') {
		return '<i class="fas fa-check text-success text-bold field-status" data-field-type="'+field+'" data-field-status="1"></i>';
	}

	return '<i class="fas fa-times text-danger text-bold field-status" data-field-type="'+field+'" data-field-status="0"></i>';
};

// handle app.modulesFieldGenerator object on generating HTML template
app.modulesFieldGenerator.template = function(data) {

	var dataString = data;

	data = JSON.parse(data);

	var datableColumnName = app.modulesHelper.getData(data, 'field_database_column');

	var html = '';
	html += "<tr class='field_"+datableColumnName+"'>";
        html += "<th class='display-none'>&nbsp;<input name='fields["+datableColumnName+"]' type='hidden' value='"+dataString+"' class='crud-field'> </th>";
        html += '<td class="field-handle-column"><span><i class="fas fa-arrows-alt drag-handle"></i></span></td>';
        html += '<td>'+app.modulesHelper.getData(data, 'field_type')+'</td>';
        html += '<td>'+datableColumnName+'</td>';
        html += '<td>'+app.modulesHelper.getData(data, 'field_visual_title')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.visibility(data, 'in_list')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.visibility(data, 'in_create')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.visibility(data, 'in_edit')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.visibility(data, 'in_show')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.visibility(data, 'is_sortable')+'</td>';
        html += '<td>'+app.modulesFieldGenerator.rule(data, 'field_validation')+'</td>';
        html += '<td><button type="button" class="btn btn-info btn-sm edit-field-settings" data-toggle="modal" data-target="#field-settings-modal" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button></td>';
        html += '<td><button type="button" class="btn btn-danger btn-sm delete-field-settings"><i class="fas fa-trash"></i></button></td>';
    html += '</tr>';

    return html;
};

// handle app.modulesFieldGenerator object on soft delete HTML template
app.modulesFieldGenerator.softDelete = function() {

	var html = '<tr><th class="display-none">&nbsp;<input name="fields[deleted_at]" type="hidden" value=\'[{"name":"field_id","value":"000000003_deleted_at"},{"name":"field_type","value":"text"},{"name":"field_visual_title","value":"'+app.trans('Deleted at')+'"},{"name":"field_database_column","value":"deleted_at"},{"name":"field_validation","value":"optional"},{"name":"field_tooltip","value":""},{"name":"in_list","value":"off"},{"name":"in_create","value":"off"},{"name":"in_edit","value":"off"},{"name":"in_show","value":"off"},{"name":"is_sortable","value":"off"}]\' class="crud-field"></th>';
                                        html += '<td><span>&nbsp;</span></td>';
                                        html += '<td>datetime</td>';
                                        html += '<td>deleted_at</td>';
                                        html += '<td>'+app.trans('Deleted at')+'</td>';
                                        html += '<td><i class="fas fa-times text-danger text-bold field-status" data-field-type="in_list" data-field-status="0"></i></td>';
                                        html += '<td></td>';
                                        html += '<td></td>';
                                        html += '<td><i class="fas fa-times text-danger text-bold field-status"  data-field-type="in_show" data-field-status="0"></i></td>';
                                        html += '<td></td>';
                                        html += '<td></td>';
                                        html += '<td></td>';
                                        html += '<td></td>';
                                    html += '</tr>';

    return html;
};