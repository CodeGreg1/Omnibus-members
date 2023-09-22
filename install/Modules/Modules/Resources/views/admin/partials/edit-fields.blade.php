@foreach($module['fields'] as $field => $attributes)
    @php
        $fieldAtts = json_decode($attributes, true);
    @endphp

    @if( ( isset($show_fields) && in_array(ModuleHelper::getValueByKey($fieldAtts, 'field_database_column'), $show_fields) ) 
    || ( isset($unshow_fields) && !in_array(ModuleHelper::getValueByKey($fieldAtts, 'field_database_column'), $unshow_fields) ) )
        <tr class="{{ ModuleHelper::getValueByKey($fieldAtts, 'field_database_column') == 'created_at' ? 'created_at_row' : 'field_'.$field }}">
            <th class="display-none">&nbsp;<input name="fields[{{ $field }}]" type="hidden" value='{{ $attributes }}' class="crud-field"></th>
            @if(isset($unshow_fields))
            <td class="field-handle-column"><span><i class="fas fa-arrows-alt drag-handle"></i></span></td>
            @else
            <td><span>&nbsp;</span></td>
            @endif
            <td>{{ ModuleHelper::getValueByKey($fieldAtts, 'field_type') }}</td>
            <td>{{ ModuleHelper::getValueByKey($fieldAtts, 'field_database_column') }}</td>
            <td>{{ ModuleHelper::getValueByKey($fieldAtts, 'field_visual_title') }}</td>
            <td>
                @if(ModuleHelper::getValueByKey($fieldAtts, 'in_list') == 'on')
                    <i class="fas fa-check text-success text-bold field-status" data-field-type="in_list" data-field-status="1"></i>
                @else
                    <i class="fas fa-times text-danger text-bold field-status" data-field-type="in_list" data-field-status="0"></i>
                @endif
            </td>
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    @if(ModuleHelper::getValueByKey($fieldAtts, 'in_create') == 'on')
                        <i class="fas fa-check text-success text-bold field-status" data-field-type="in_create" data-field-status="1"></i>
                    @else
                        <i class="fas fa-times text-danger text-bold field-status" data-field-type="in_create" data-field-status="0"></i>
                    @endif
                @endif
            </td>
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    @if(ModuleHelper::getValueByKey($fieldAtts, 'in_edit') == 'on')
                        <i class="fas fa-check text-success text-bold field-status" data-field-type="in_edit" data-field-status="1"></i>
                    @else
                        <i class="fas fa-times text-danger text-bold field-status" data-field-type="in_edit" data-field-status="0"></i>
                    @endif
                @endif
            </td>
            <td>
                @if(ModuleHelper::getValueByKey($fieldAtts, 'in_show') == 'on')
                    <i class="fas fa-check text-success text-bold field-status" data-field-type="in_show" data-field-status="1"></i>
                @else
                    <i class="fas fa-times text-danger text-bold field-status" data-field-type="in_show" data-field-status="0"></i>
                @endif
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    @if(ModuleHelper::getValueByKey($fieldAtts, 'is_sortable') == 'on')
                        <i class="fas fa-check text-success text-bold field-status" data-field-type="is_sortable" data-field-status="1"></i>
                    @else
                        <i class="fas fa-times text-danger text-bold field-status" data-field-type="is_sortable" data-field-status="0"></i>
                    @endif
                @endif
            </td>
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    @if(ModuleHelper::getValueByKey($fieldAtts, 'field_validation') == 'required')
                        <i class="fas fa-check text-success text-bold field-status" data-field-type="field_validation" data-field-status="1"></i>
                    @else
                        <i class="fas fa-times text-danger text-bold field-status" data-field-type="field_validation" data-field-status="0"></i>
                    @endif
                @endif
            </td>
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    <button type="button" class="btn btn-info btn-sm edit-field-settings" data-toggle="modal" data-target="#field-settings-modal" data-backdrop="static" data-keyboard="false"><i class="fas fa-edit"></i></button>
                @endif
            </td>
            <td>
                @if(!in_array($field, TableColumns::EXCLUDED))
                    <button type="button" class="btn btn-danger btn-sm delete-field-settings"><i class="fas fa-trash"></i></button>
                @endif
            </td>
        </tr>
    @endif
@endforeach