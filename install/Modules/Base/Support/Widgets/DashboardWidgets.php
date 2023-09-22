<?php

namespace Modules\Base\Support\Widgets;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardWidgets 
{
    /**
     * @var array $attributes
     */
	protected $attributes = [];

    /**
     * @var string RELATIONSHIP_PREFIX_NAME
     */
    const RELATIONSHIP_PREFIX_NAME = 'relationship_field';

    /**
     * @var string DAY
     */
    const DAY = 'day';

    /**
     * @var string WEEK
     */
    const WEEK = 'week';

    /**
     * @var string MONTH
     */
    const MONTH = 'month';

    /**
     * @var string YEAR
     */
    const YEAR = 'year';

    /**
     * @var array $periods
     */
    protected $periods = [
        self::DAY,
        self::WEEK,
        self::MONTH,
        self::YEAR,
    ];

	public function __construct($attributes) {
		$this->attributes = $attributes;
	}

    /**
     * Get widget base on attribute type
     * 
     * @return array
     */
	public function get() 
	{
		foreach($this->attributes as $key => $attribute) {
            if (class_exists($attribute['model'])) {

                if($attribute['type'] == 'latest_records') {
                    $this->latestRecordsWidget($key, $attribute);
                }

                if($attribute['type'] == 'counter') {
                    $this->counterWidget($key, $attribute);
                }

                if(in_array($attribute['type'], ['line', 'bar', 'pie'])) {
                    $this->chartWidget($key, $attribute);
                }
            }
        }

        return $this->attributes;
	}

    /**
     * Generate chart widgets through array
     * 
     * @param int $key
     * @param array $chart
     * 
     * @return array
     */
	protected function chartWidget($key, $chart) 
	{
		$labels = $this->getLabels($chart);

        $query = $chart['model']::when(isset($chart['filter_field']), function($query) use($chart) {

            if (isset($widget['filter_days'])) {
                return $query->when(isset($chart['filter_days']), function($query) use($chart) {
                
                    $query = $query->orderBy($chart['filter_field']);
                    if(isset($chart['filter_years'])) {
                        $query = $query->where(
                            $chart['filter_field'],
                            '>=',
                            now()->subYears($chart['filter_years'])
                        );
                    } else {

                        if($chart['filter_days'] == 'today') {
                            $query = $query->where(
                                $chart['filter_field'],
                                '>=',
                                now()->format('Y-m-d')
                            );
                        } else {
                            $query = $query->where(
                                $chart['filter_field'],
                                '>=',
                                now()->subDays($chart['filter_days'])
                            );
                        }
                        
                    }
                });
            }
            


            if(isset($chart['filter_period'])) {

                $datesBetween = [];

                switch ($chart['filter_period']) {
                    case 'last_week':
                        $datesBetween = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];
                        break;
                    case 'this_week':
                        $datesBetween = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                        break;
                    case 'last_month':
                        $datesBetween = [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];
                        break;
                    case 'this_month':
                        $datesBetween = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMOnth()];
                        break;
                    case 'last_year':
                        $datesBetween = [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()];
                        break;
                    case 'this_year':
                        $datesBetween = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
                        break;
                }

                if(count($datesBetween) == 2) {
                    return $query->whereBetween($chart['filter_field'], $datesBetween); 
                }
                
            }
            
        })
        ->get();

        $labelCollection = collect($labels);

        $data = $query->groupBy(function($item) use($chart) {
            if($chart['group_by_period'] == self::DAY) {
                return \Carbon\Carbon::parse($item->{$chart['group_by_field']})->format('Y-m-d');
            }

            if($chart['group_by_period'] == self::WEEK) {
                return \Carbon\Carbon::parse($item->{$chart['group_by_field']})->format('W');
            }

            if($chart['group_by_period'] == self::MONTH) {
                return \Carbon\Carbon::parse($item->{$chart['group_by_field']})->format('F');
            }

            if($chart['group_by_period'] == self::YEAR) {
                return \Carbon\Carbon::parse($item->{$chart['group_by_field']})->format('Y');
            }

            if(!in_array($chart['group_by_period'], $this->periods)) {
                return $item->{$chart['group_by_field']};
            }
        })
        ->map(function($entries) use($chart) {
            return $entries->{$chart['aggregate_function'] ?? 'count'}($chart['aggregate_field'] ?? '');
        });

        switch ($chart['group_by_period']) {
            case 'week': 
                $data = $data->toArray();
                $mergedData = [];
                foreach($labelCollection as $itemKey => $value) {
                    if(array_key_exists($itemKey, $data)) {
                        $mergedData['week ' . $itemKey] = number_format($data[$itemKey], 2);
                    } else {
                        if(isset($chart['show_blank_data'])) {
                            $mergedData['week ' . $itemKey] = $value;
                        }
                    }
                }
                break;
            default:
                $data = $data->toArray();

                $mergedData = [];

                if(count($labelCollection) && in_array($chart['group_by_period'], $this->periods)) {
                    foreach($labelCollection as $itemKey => $value) {
                        if(array_key_exists($itemKey, $data)) {
                            // echo 4;die;
                            $mergedData[$itemKey] = number_format($data[$itemKey], 2);
                        } else {
                            // echo 3;die;
                            if(isset($chart['show_blank_data'])) {
                                $mergedData[$itemKey] = $value;
                            }
                        }
                    }
                } else { //for not group by periods
                    $mergedData = $data;
                }
        }

        $this->attributes[$key]['result'] = [
            'labels' => array_keys($mergedData),
            'data' => array_values($mergedData)
        ];
	}

    /**
     * Generate latest records widget through array
     * 
     * @param int $key
     * @param array $attribute
     *
     * @return array
     */
	protected function latestRecordsWidget($key, $attribute) 
	{    
		$this->attributes[$key]['result'] = $attribute['model']::latest()
            ->when(isset($this->attributes[$key]['fields']), function($query) use($attribute, $key) {
                $with = [];

                foreach($this->attributes[$key]['fields'] as $fieldSettings) {
                    if(isset($fieldSettings['relationship_name']) && $fieldSettings['relationship_name'] != '') {
                        $with[] = $fieldSettings['relationship_name'];
                    }
                }

                if(count($with)) {
                    return $query->with($with);
                }

                return $query;
            })
            ->when(isset($attribute['filter_value']), function ($query) use ($attribute) {
                if($attribute['filter_value'] == 'null') {
                    return $query->whereNull($attribute['filter_field']);
                }

                if($attribute['filter_value'] == 'not_null') {
                    return $query->whereNotNull($attribute['filter_field']);
                }

                return $query->where($attribute['filter_field'], $attribute['filter_value']);
                
            })
            ->take($attribute['total_records'])
            ->get();

        $this->attributes[$key]['result']->map(function($result) use($key) {

            foreach($this->attributes[$key]['fields'] as $field => $fieldSettings) {

                $fieldValues = [];

                // Generate shortcode with value from relationship table
                if(isset($fieldSettings['relationship_name']) && $fieldSettings['relationship_name'] != '' && isset($fieldSettings['relationship_fields']) && count($fieldSettings['relationship_fields'])) {

                    $totalRelationshipFields = count($fieldSettings['relationship_fields']);
                    
                    $countRelationshipField = 0;
                    foreach($fieldSettings['relationship_fields'] as $relationshipField) {
                        $shortCodeTemplate = sprintf('$'.self::RELATIONSHIP_PREFIX_NAME.'.%s$', $relationshipField);

                        if(isset($fieldSettings['relationship_name'])) {

                            $countRelationshipField++;

                            if(isset($result->{$fieldSettings['relationship_name']}->{$relationshipField})) {
                                $fieldValues[$shortCodeTemplate] = $result->{$fieldSettings['relationship_name']}->{$relationshipField};
                            } else {
                                if($countRelationshipField == $totalRelationshipFields && isset($result->{$fieldSettings['relationship_name']}->id)) {
                                    $fieldValues[$shortCodeTemplate] = 'ID with ' . $result->{$fieldSettings['relationship_name']}->id . '';
                                } else {
                                    $fieldValues[$shortCodeTemplate] = '';
                                }
                            }
                        } else {
                            throw new Exception("Field name: $field missing 'relationship_name' key.", 1);
                        }
                    }

                    preg_match_all('/\$(.*?)\$/s', $fieldSettings['display_format'], $match);

                    $mainFields = [];
                    if(isset($match[1])) {
                        foreach($match[1] as $mainField) {
                            if(!Str::contains($mainField, self::RELATIONSHIP_PREFIX_NAME . '.')) {
                                $mainFields[] = $mainField;
                            }
                        }
                    } else {
                        $mainFields[] = $field;
                    }

                    foreach($mainFields as $mainField) {
                        $mainFieldShortCodeTemplate = sprintf('$%s$', $mainField);
                        
                        //generate main field shortcode with value
                        if(isset($result->{$mainField})) {

                            if($result->{$mainField} != '') {
                                $fieldValues[$mainFieldShortCodeTemplate] = $result->{$mainField};
                            } else {
                                $fieldValues[$mainFieldShortCodeTemplate] = __('N/A');
                            }
                            
                        } else {
                            $fieldValues[$mainFieldShortCodeTemplate] = __('N/A');
                        }

                    }

                    $result->{$field . '_custom'} = $this->latestRecordsWidgetReplace($fieldValues, $fieldSettings);
                } else {

                    if(isset($result->{$field}) && $result->{$field} != '') {
                        $result->{$field . '_custom'} = $result->{$field};
                    } else {
                        $result->{$field . '_custom'} = __('N/A');
                    }

                    if(isset($fieldSettings['format_type']) && $fieldSettings['format_type'] == 'timeago') {
                        $result->{$field . '_custom'} = Carbon::parse($result->{$field})->diffForHumans();
                    }

                    if(isset($fieldSettings['format_type']) && $fieldSettings['format_type'] == 'date') {
                        $result->{$field . '_custom'} = Carbon::parse($result->{$field})->format(setting('date_format'));
                    }

                    if(isset($fieldSettings['format_type']) && $fieldSettings['format_type'] == 'datetime') {
                        $result->{$field . '_custom'} = Carbon::parse($result->{$field})->toDateTimeString();
                    }
                }
                   
            }
        });

        if(isset($attribute['fields']) && count($attribute['fields']) < 1) {
            unset($this->attributes[$key]['fields']);
        }
	}

    /**
     * Latest records search and replace shortcode
     * 
     * @var array $replacements
     * @var array $fieldSettings
     * 
     * return string
     */
    protected function latestRecordsWidgetReplace($replacements, $fieldSettings) 
    {   
        $subject = $fieldSettings['display_format'];

        foreach($replacements as $key => $value) {
            $subject = str_replace($key, $value, $subject);
        }

        return $subject;
    }

    /**
     * Generate counter widgets through array
     * 
     * @param int $key 
     * @param array $attribute
     * 
     * @return array
     */
	protected function counterWidget($key, $attribute) 
	{
		$this->attributes[$key]['result'] = $attribute['model']::when(isset($attribute['filter_field']), function ($query) use ($attribute) {
                        
	        if (isset($attribute['filter_minutes'])) {
	            return $query->where(
	                $attribute['filter_field'],
	                '>=',
	                now()->subMinutes($attribute['filter_minutes'] )->format('Y-m-d H:i:s')
	            );
	        }

	        if (isset($attribute['filter_days'])) {

	            return $query->when($attribute['filter_days'] == 'today', function ($query) use ($attribute) {
	                return $query->where(
	                    $attribute['filter_field'],
	                    '>=',
	                    now()->format('Y-m-d')
	                );
	            }, function($query) use($attribute) {
	                return $query->where(
	                    $attribute['filter_field'],
	                    '>=',
	                    now()->subDays($attribute['filter_days'] )->format('Y-m-d')
	                );
	            });
	        }

	        if(isset($attribute['filter_period'])) {

                $datesBetween = [];

                switch ($attribute['filter_period']) {
                    case 'last_week':
                        $datesBetween = [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()];
                        break;
                    case 'this_week':
                        $datesBetween = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                        break;
                    case 'last_month':
                        $datesBetween = [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];
                        break;
                    case 'this_month':
                        $datesBetween = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMOnth()];
                        break;
                    case 'last_year':
                        $datesBetween = [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()->endOfYear()];
                        break;
                    case 'this_year':
                        $datesBetween = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
                        break;
                }

                if(count($datesBetween) == 2) {
                    return $query->whereBetween($attribute['filter_field'], $datesBetween); 
                }
                
            }

	        return $query->when(isset($attribute['filter_value']), function ($query) use ($attribute) {
	            if($attribute['filter_value'] == 'null') {
	                return $query->whereNull($attribute['filter_field']);
	            } else {
	                return $query->where($attribute['filter_field'], $attribute['filter_value']);
	            }
	        });
	    })
	    ->{$attribute['aggregate_function'] ?? 'count'}($attribute['aggregate_field'] ?? '*');
	}

    /**
     * Get chart labels
     * 
     * @param array $chart
     * 
     * @return array
     */
	protected function getLabels($chart) 
    {
        $labels = [];

        if($chart['group_by_period'] == 'day') {

            if(isset($chart['filter_days'])) {

                if($chart['filter_days'] == 'today') {
                    $labels[now()->format('Y-m-d')] = 0;
                } else {
                    for ($x = 0; $x < $chart['filter_days']; $x++) {
                        $labels[now()->subDays($x)->format('Y-m-d')] = 0;
                    }

                    ksort($labels);
                }
            }

            if(isset($chart['filter_period'])) {

                switch ($chart['filter_period']) {
                    case 'last_week':
                        $start = Carbon::now()->subWeek()->startOfWeek();
                        $end = Carbon::now()->subWeek()->endOfWeek();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_last_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_last_week;
                        }

                        break;
                    case 'this_week':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_this_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_this_week;
                        }

                        break;
                    case 'last_month':
                        $start = Carbon::now()->subMonth()->startOfMonth();
                        $end = Carbon::now()->subMonth()->endOfMonth();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_last_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_last_month;
                        }

                        break;
                    case 'this_month':
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMOnth();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_this_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_this_month;
                        }

                        break;
                    case 'last_year':
                        $start = Carbon::now()->subYear()->startOfYear();
                        $end = Carbon::now()->subYear()->endOfYear();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_last_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_last_year;
                        }

                        break;
                    case 'this_year':
                        $start = Carbon::now()->startOfYear();
                        $end = Carbon::now()->endOfYear();

                        $labels[$start->format('Y-m-d')] = 0;

                        day_period_this_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y-m-d')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto day_period_this_year;
                        }

                        break;
                }
            }            
        }

        if($chart['group_by_period'] == 'week') {
            if(isset($chart['filter_days'])) {

                if($chart['filter_days'] == 'today') {
                    $labels[now()->format('W')] = 0;
                } else {
                    $weeks = $chart['filter_days'] / 7;
                    $weeks = (int)$weeks;

                    for ($x = -($weeks-1); $x < 1; $x++) {
                        $week = ($x + now()->format('W'));
                        $labels[$week] = 0;
                    }
                }
                
            }

            if(isset($chart['filter_period'])) {
                switch ($chart['filter_period']) {
                    case 'last_week':
                        $start = Carbon::now()->subWeek()->startOfWeek();
                        $end = Carbon::now()->subWeek()->endOfWeek();

                        $labels[$start->format('W')] = 0;

                        week_period_last_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_last_week;
                        }

                        break;
                    case 'this_week':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek();

                        $labels[$start->format('W')] = 0;

                        week_period_this_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_this_week;
                        }

                        break;
                    case 'last_month':
                        $start = Carbon::now()->subMonth()->startOfMonth();
                        $end = Carbon::now()->subMonth()->endOfMonth();

                        $labels[$start->format('W')] = 0;

                        week_period_last_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_last_month;
                        }

                        break;
                    case 'this_month':
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMOnth();

                        $labels[$start->format('W')] = 0;

                        week_period_this_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_this_month;
                        }

                        break;
                    case 'last_year':
                        $start = Carbon::now()->subYear()->startOfYear();
                        $end = Carbon::now()->subYear()->endOfYear();

                        $labels[$start->format('W')] = 0;

                        week_period_last_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_last_year;
                        }

                        break;
                    case 'this_year':
                        $start = Carbon::now()->startOfYear();
                        $end = Carbon::now()->endOfYear();

                        $labels[$start->format('W')] = 0;

                        week_period_this_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('W')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto week_period_this_year;
                        }

                        break;
                }
            }
        }

        if($chart['group_by_period'] == 'month') {
            if(isset($chart['filter_days'])) {

                if($chart['filter_days'] == 'today') {
                    $month = Carbon::today();
                    $labels[$month->monthName] = 0;
                } else {
                    $lastMonths = $chart['filter_days']/30;
                    $lastMonths = (int)$lastMonths;

                    $labels = [];
                    for ($i = $lastMonths; $i >= 0; $i--) {
                        $month = Carbon::today()->startOfMonth()->subMonth($i);

                        $labels[$month->monthName] = 0;
                    } 
                }
            }

            if(isset($chart['filter_period'])) {

                switch ($chart['filter_period']) {
                    case 'last_week':
                        $start = Carbon::now()->subWeek()->startOfWeek();
                        $end = Carbon::now()->subWeek()->endOfWeek();

                        $labels[$start->monthName] = 0;

                        month_period_last_week:
                        $start = $start->addDay(1);
                        $labels[$start->monthName] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto month_period_last_week;
                        }

                        break;
                    case 'this_week':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek();

                        $labels[$start->monthName] = 0;

                        month_period_this_week:
                        $start = $start->addDay(1);
                        $labels[$start->monthName] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto month_period_this_week;
                        }

                        break;
                    case 'this_month':
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMOnth();

                        $labels[$start->monthName] = 0;

                        month_period_this_month:
                        $start = $start->addDay(1);
                        $labels[$start->monthName] = 0;
                        if ($end->format('m') != $start->format('m')) {
                           goto month_period_this_month;
                        }

                        break;
                    case 'last_month':
                        $start = Carbon::now()->subMonth()->startOfMonth();
                        $end = Carbon::now()->subMonth()->endOfMonth();

                        $labels[$start->monthName] = 0;

                        month_period_last_month:
                        $start = $start->addDay(1);
                        $labels[$start->monthName] = 0;
                        if ($end->format('m') != $start->format('m')) {
                           goto month_period_last_month;
                        }

                        break;
                    case 'last_year':
                        $start = Carbon::now()->subYear()->startOfYear();
                        $end = Carbon::now()->subYear()->endOfYear();

                        $labels[$start->format('F')] = 0;

                        month_period_last_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('F')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto month_period_last_year;
                        }

                        break;
                    case 'this_year':
                        $start = Carbon::now()->startOfYear();
                        $end = Carbon::now()->endOfYear();

                        $labels[$start->format('F')] = 0;

                        month_period_this_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('F')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto month_period_this_year;
                        }

                        break;
                }
            }
        }

        if($chart['group_by_period'] == 'year') {
            if(isset($chart['filter_years'])) {
                for ($x = -$chart['filter_years']; $x < 1; $x++) {
                  $labels[now()->addYears($x)->format('Y')] = 0;
                } 
            }

            if(isset($chart['filter_period'])) {

                switch ($chart['filter_period']) {
                    case 'last_week':
                        $start = Carbon::now()->subWeek()->startOfWeek();
                        $end = Carbon::now()->subWeek()->endOfWeek();

                        $labels[$start->format('Y')] = 0;

                        year_period_last_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto year_period_last_week;
                        }

                        break;
                    case 'this_week':
                        $start = Carbon::now()->startOfWeek();
                        $end = Carbon::now()->endOfWeek();

                        $labels[$start->format('Y')] = 0;

                        year_period_this_week:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto year_period_this_week;
                        }

                        break;
                    case 'this_month':
                        $start = Carbon::now()->startOfMonth();
                        $end = Carbon::now()->endOfMOnth();

                        $labels[$start->format('Y')] = 0;

                        year_period_this_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('m') != $start->format('m')) {
                           goto year_period_this_month;
                        }

                        break;
                    case 'last_month':
                        $start = Carbon::now()->subMonth()->startOfMonth();
                        $end = Carbon::now()->subMonth()->endOfMonth();

                        $labels[$start->format('Y')] = 0;

                        year_period_last_month:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('m') != $start->format('m')) {
                           goto year_period_last_month;
                        }

                        break;
                    case 'last_year':
                        $start = Carbon::now()->subYear()->startOfYear();
                        $end = Carbon::now()->subYear()->endOfYear();

                        $labels[$start->format('Y')] = 0;

                        year_period_last_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto year_period_last_year;
                        }

                        break;
                    case 'this_year':
                        $start = Carbon::now()->startOfYear();
                        $end = Carbon::now()->endOfYear();

                        $labels[$start->format('Y')] = 0;

                        year_period_this_year:
                        $start = $start->addDay(1);
                        $labels[$start->format('Y')] = 0;
                        if ($end->format('Y-m-d') != $start->format('Y-m-d')) {
                           goto year_period_this_year;
                        }

                        break;
                }

            }


        }

        return $labels;
    }
}