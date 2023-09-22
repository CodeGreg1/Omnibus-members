<?php

namespace Modules\DashboardWidgets\Services\Helper;

use Illuminate\Support\Str;
use Modules\Modules\Support\TableColumns;
use Modules\DashboardWidgets\Services\Helper\EditWidgetHelper;

class EditWidgetHelper {

    /**
     * @var array $hasDataAggregatingFunctionTypes
     */
	protected $hasDataAggregatingFunctionTypes = [
        'line',
        'bar',
        'pie',
        'counter'
    ];

    /**
     * @var array $hasGroupByColumnTypes
     */
    protected $hasGroupByColumnTypes = [
        'line',
        'bar',
        'pie'
    ];

    /**
     * @var array $hasFilterFieldTypes
     */
    protected $hasFilterFieldTypes = [
        'counter'
    ];

    /**
     * @var array $hasShowBlankDataTypes
     */
    protected $hasShowBlankDataTypes = [
        'line',
        'bar',
        'pie'
    ];

    /**
     * @var array $hasTotalRecordsToShowTypes
     */
    protected $hasTotalRecordsToShowTypes = [
        'latest_records'
    ];

    /**
     * @var array $hasLoadRelationshipsTypes
     */
    protected $hasLoadRelationshipsTypes = [
        'latest_records'
    ];

    /**
     * @var array $hasFieldToShowTypes
     */
    protected $hasFieldToShowTypes = [
        'latest_records'
    ];

    /**
     * @var array $hasViewMoreTypes
     */
    protected $hasViewMoreTypes = [
        'latest_records'
    ];

    /**
     * @var array $hasHasFilteredDataTypes
     */
    protected $hasHasFilteredDataTypes = [
        'line',
        'bar',
        'pie',
        'counter'
    ];

    /**
     * @var array $sumAvgTypesTrigger
     */
    protected $sumAvgTypesTrigger = [
        'integer',
        'decimal',
        'float'
    ];

    /**
     * @var array $excludedColumns
     */
    protected $excludedColumns = [
        'id',
        'password',
        'auth_id',
        'authy_status',
        'authy_country_code',
        'authy_phone'
    ];

    /**
     * @var array $groupPeriodsTrigger
     */
    protected $groupPeriodsTrigger = [
        'timestamp'
    ];

    /**
     * @var string $displayNoneClassName
     */
    protected $displayNoneClassName = 'display-none';
    
    /**
     * Set the group by column as selected
     * 
     * @param array $attributes
     * @param string $groupKey
     * 
     * @return string|null
     */
    public static function selectedGroupByColumn($attributes, $groupKey) 
    {
        if(isset($attributes['group_by_column']) && $attributes['group_by_column'] == $groupKey) {
            return 'selected';
        }
    }

    /**
     * Set the filter field as selected
     * 
     * @param array $attributes
     * @param string $filterField
     * 
     * @return string|null
     */
    public static function selectedFilterField($attributes, $filterField) 
    {
        if(isset($attributes['filter_field']) && $attributes['filter_field'] == $filterField) {
            return 'selected';
        }
    }

    /**
     * Set the aggregating function as selected
     * 
     * @param array $attributes
     * @param string $aggregateFunction
     * 
     * @return string|null
     */
    public static function selectedDataAggregatingFunction($attributes, $aggregateFunction) 
    {
        if(isset($attributes['aggregate_function']) && in_array($aggregateFunction, $attributes)) {
            return 'selected';
        }
    }

    /**
     * Generate filter field options
     * 
     * @param string $namespace
     * 
     * @return array
     */
    public static function getFilterFieldOptions($namespace) 
    {
        $editWidgetHelper = new EditWidgetHelper;

        $options = [];

        $columns = (new TableColumns($namespace, true, true))->get();

        foreach($columns as $column => $columnSettings) {
            if(!in_array($column, $editWidgetHelper->excludedColumns)) {
                $options[$column] = $column;
            }
        }

        return $options;
    }

    /**
     * Generate aggregating function and group by options
     * 
     * @param string $namespace
     * 
     * @return array
     */
    public static function getDataAggregatingFunctionAndGroupByColumnOptions($namespace) 
    {
        $editWidgetHelper = new EditWidgetHelper;

        $aggregateFunctionOptions = [
            'count' => 'count()'
        ];

        $groupByColumnOptions = [];

        $columns = (new TableColumns($namespace, true, true))->get();

        foreach($columns as $column => $columnSettings) {

            if(!Str::contains($column, '_id') && !in_array($column, $editWidgetHelper->excludedColumns) && in_array($column, $editWidgetHelper->sumAvgTypesTrigger)) {
                $aggregateFunctionOptions['sum|' . $column] = 'sum('.$column.')';
                $aggregateFunctionOptions['avg|' . $column] = 'avg('.$column.')';
            }

            if(!Str::contains($column, '_id') && in_array($columnSettings['type'], $editWidgetHelper->groupPeriodsTrigger)) {
                $groupByColumnOptions['DAY|' . $column] = 'DAY('.$column.')';
                $groupByColumnOptions['WEEK|' . $column] = 'WEEK('.$column.')';
                $groupByColumnOptions['MONTH|' . $column] = 'MONTH('.$column.')';
                $groupByColumnOptions['YEAR|' . $column] = 'YEAR('.$column.')';
            }
        }

        foreach($columns as $column => $columnSettings) {
            if(!Str::contains($column, '_id') && !in_array($column, $editWidgetHelper->excludedColumns) && !in_array($columnSettings['type'], $editWidgetHelper->groupPeriodsTrigger)) {
                $groupByColumnOptions[$column] = $column;
            }
        }

        return [
            'aggregate_function_options' => $aggregateFunctionOptions,
            'group_by_column_options' => $groupByColumnOptions
        ];
    }

    /**
     * Get the column name value and set default display if not active
     * 
     * @param array $attributes
     * @param string $column
     * @param string $columnDisplay
     * 
     * @return string
     */
    public static function columnNameValue($attributes, $column, $columnDisplay) 
    {
        if(isset($attributes['fields'][$column]['column_name']) && isset($attributes['fields'][$column]) && in_array($column, array_keys($attributes['fields']))) {
            return $attributes['fields'][$column]['column_name'];
        }

        return $columnDisplay;
    }

    /**
     * Selected relationship fields
     * 
     * @param array $attributes
     * @param string $column
     * @param string $field
     * 
     * @return string
     */
    public static function selectedRelationshipFields($attributes, $column, $field) 
    {
        if(isset($attributes['fields'][$column]) && in_array($field, $attributes['fields'][$column]['relationship_fields'])) {
            return 'selected';
        }
    }

    /**
     * Get HTML format type customized value and set as default
     * - Useful for changing format type so that the customized value will still display
     * 
     * @param array $attributes
     * @param string $column
     * 
     * @return string
     */
    public static function getDisplayFormatDefaultValue($attributes, $column) 
    {
        $default = '$'.$column.'$';

        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type']) && $attributes['fields'][$column]['format_type'] == 'html' && $attributes['fields'][$column]['display_format'] != $default) {
            return $attributes['fields'][$column]['display_format'];
        }

        return $default;
    }

    /**
     * Get table column for the field
     * 
     * @param array $attributes
     * @param string $column
     * 
     * @return array|null
     */
    public static function getTableColumns($attributes, $column) 
    {
        if(isset($attributes['fields'][$column]) && $attributes['fields'][$column]['relationship_model'] !== null) {
            return (new TableColumns($attributes['fields'][$column]['relationship_model'], true, true))->get();
        }
    }

    /**
     * Get the selected relationship name
     * 
     * @param array $attributes
     * @param string $column
     * @param string $relationshipName
     * 
     * @return string
     */
    public static function selectedRelationshipName($attributes, $column, $relationshipName) 
    {
        if(isset($attributes['fields'][$column]) && $attributes['fields'][$column]['relationship_name'] == $relationshipName) {
            return 'selected';
        }
    }

    /**
     * Get the selected relationship model
     * 
     * @param array $attributes
     * @param string $column
     * @param string $namespace - Model namespace
     * 
     * @return string
     */
    public static function selectedRelationshipModel($attributes, $column, $namespace) 
    {
        if(isset($attributes['fields'][$column]) && $attributes['fields'][$column]['relationship_model'] == $namespace) {
            return 'selected';
        }
    }

    /**
     * Get disabled field to show relationship field
     * 
     * @param array $attributes
     * @param string $column
     * 
     * @return string
     */
    public static function isDisabledFieldToShowRelationshipsField($attributes, $column) 
    {
        if(isset($attributes['fields'])) {
            $loadedRelationships = (new EditWidgetHelper)->getLoadedRelationshipName($attributes['fields']);
        
            return in_array($column, array_keys($attributes['fields'])) && $loadedRelationships != '' ? '' : 'disabled';
        } else { //from other type then switching to latest_records
            return 'disabled';
        }
    }

    /**
     * Get selected format type
     * 
     * @param array $attributes
     * @param string $column
     * @param string $formatType
     * 
     * @return string
     */
    public static function selectedFormatType($attributes, $column,  $formatType) 
    {   
        if(isset($attributes['fields'][$column]['format_type'])) {
            return isset($attributes['fields'][$column]) && $attributes['fields'][$column]['format_type'] == $formatType ? 'selected' : '';
        }
    }

    /**
     * Get status disabled display format
     * 
     * @param array $attributes
     * @param string $column
     * @param string $formatType
     * 
     * @return string
     */
    public static function isDisabledDisplayFormat($attributes, $column) 
    {

        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type'])) {
            if($attributes['fields'][$column]['format_type'] == 'html') {
                return '';
            }
        }

        return 'disabled';
    }

    /**
     *  Get the width value for the field from attributes
     * 
     * @param array $attributes
     * @param string $column
     * 
     * @return int
     */
    public static function widthValue($attributes, $column) 
    {
        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['width'])) {
            return $attributes['fields'][$column]['width'];
        }
    }

    /**
     *  Display format value
     * 
     * @param array $attributes
     * @param string $column
     * 
     * @return string
     */
    public static function displayFormatValue($attributes, $column) 
    {
        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type']) && $attributes['fields'][$column]['format_type'] == 'html') {

            if( in_array($column, array_keys($attributes['fields'])) ) {
                return $attributes['fields'][$column]['display_format'];
            }

        }

        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type']) && $attributes['fields'][$column]['format_type'] == 'timeago') {
            return 'timeago';
        }

        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type']) && $attributes['fields'][$column]['format_type'] == 'date') {
            return 'date';
        }

        if(isset($attributes['fields'][$column]) && isset($attributes['fields'][$column]['format_type']) && $attributes['fields'][$column]['format_type'] == 'datetime') {
            return 'datetime';
        }

        return '$' . $column . '$';
    }

    /**
     * Get loaded relationship names and formatted with comma separated
     * 
     * @param array $fields
     * 
     * @return string
     */
    public function getLoadedRelationshipName(array $fields) 
    {   
        $result = [];
        foreach($fields as $field) {
            if(isset($field['relationship_name']) && $field['relationship_name'] != '') {
                $result[] = $field['relationship_name'];
            }
        }

        return implode(',', $result);
    }

    /**
     * Visibility it will show css class if not visible
     * 
     * @param string $widgetType
     * @param string $fieldType
     * 
     * @return string
     */
    public static function visibility($widgetType, $fieldType) 
    {
        $helper = new EditWidgetHelper;

        return (new EditWidgetHelper)->isShowFieldOnLoad($widgetType, $fieldType) 
            ? '' 
            : $helper->displayNoneClassName;
    }

    /**
     * For disabled field using visibility result if has a display-none class result then the function will return disabled
     * 
     * @param string $widgetType
     * @param string $fieldType
     * 
     * @return string
     */
    public static function disabledField($widgetType, $fieldType) 
    {
        $helper = new EditWidgetHelper;

        return static::visibility($widgetType, $fieldType) == $helper->displayNoneClassName ? 'disabled': '';
    }

    /**
     * Determine if field show on load
     * 
     * @param string $widgetType
     * @param string $fieldType
     * 
     * @return boolean
     */
    public function isShowFieldOnLoad($widgetType, $fieldType) 
    {	
    	if($fieldType == 'aggregate_function') {

            $helper = new EditWidgetHelper;

    		return $this->isFieldTypeExist(
    			$widgetType, 
    			$helper->hasDataAggregatingFunctionTypes
    		);
    	}

        if($fieldType == 'group_by_column') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasGroupByColumnTypes
            );
        }

        if($fieldType == 'filter_field') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasFilterFieldTypes
            );
        }

        if($fieldType == 'show_blank_data') {
            return $this->isFieldTypeExist(
                $widgetType, 
                $this->hasShowBlankDataTypes
            );
        }

        if($fieldType == 'total_records') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasTotalRecordsToShowTypes
            );
        }

        if($fieldType == 'loaded_relationships') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasLoadRelationshipsTypes
            );
        }

        if($fieldType == 'fields_to_show') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasFieldToShowTypes
            );
            
        }

        if($fieldType == 'filter_data') {

            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasHasFilteredDataTypes
            );
            
        }

        if($fieldType == 'view_more') {
            $helper = new EditWidgetHelper;

            return $this->isFieldTypeExist(
                $widgetType, 
                $helper->hasViewMoreTypes
            );
        }
    }

    /**
     * Check if field type is exist
     * 
     * @param string $type
     * @param array $options
     * 
     * @return boolean
     */
    protected function isFieldTypeExist($type, $options) 
    {
        return in_array(
            $type, 
            $options
        );
    }

}