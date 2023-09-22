<?php

namespace Modules\DashboardWidgets\Services;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Support\Widgets\ChartType;
use Modules\DashboardWidgets\Services\Chart\ChartHelper;
use Modules\DashboardWidgets\Services\Helper\WidgetHelper;
use Torann\LaravelRepository\Repositories\AbstractRepository;

class DashboardWidgetGenerator {

	/**
	 * @var AbstractRepository $repo
	 */
	protected $repo;

	/**
	 * @var array $attributes
	 */
	protected $attributes = [];

	/**
	 * @var string $defaultGroupByFieldFormat
	 */
	protected $defaultGroupByFieldFormat = 'Y-m-d H:i:s';
	
	/**
	 * @var string $defaultFilterField
	 */
	protected $defaultFilterField = 'created_at';
	
	/**
	 * @var array $aggregateFunctionSumAvg
	 */
	protected $aggregateFunctionSumAvg = [
		'sum',
		'avg'
	];

	/**
	 * @var array $stubs
	 */
	protected $stubs = [
		'Widgets.php.stub'
	];

	/**
	 * @var string $stubFolder
	 */
	protected $stubFolder = '/Modules/DashboardWidgets/Stubs/';
	
	/**
	 * @var string $copyToFolder
	 */
	protected $copyToFolder = '/Modules/Dashboard/Services/';

	/**
	 * @param AbstractRepository $repo
	 * @param array $attributes
	 */
	public function __construct($repo, $attributes) 
	{
		$this->repo = $repo;
		$this->attributes = $attributes;
		$this->filesystem = new Filesystem;
	}

	/**
	 * Generate dashboard widget by type
	 * 
	 * @return Model
	 */
	public function generate() 
	{	
		if(in_array($this->attributes['type'], ChartType::lists())) {
			$result = $this->chart();
		}

		if($this->attributes['type'] == 'counter') {
			$result = $this->counter();
		}

		if($this->attributes['type'] == 'latest_records') {
			$result = $this->latestRecords();
		}

		if(isset($this->attributes['id'])) {
			$repo = $this->repo->find($this->attributes['id']);
			$this->repo->update($repo, [
				'name' => $result['title'],
				'type' => $result['type'],
				'attributes' => json_encode($result),
			]);
			
			$repo->fresh();
		} else {
			$repo = $this->repo->create([
				'name' => $result['title'],
				'type' => $result['type'],
				'attributes' => json_encode($result),
				'ordering' => ($this->repo->count() + 1)
			]);
		}

		$this->generateCode();

		return $repo;
	}

	/**
	 * Latest records widget generator
	 * 
	 * @return array
	 */
	protected function latestRecords() 
	{
		$widgetHelper = new WidgetHelper;

		$result = [];
		$result['title'] = $this->attributes['name'];
		$result['type'] = $this->attributes['type'];
		$result['model'] = $this->attributes['data_source_model'];
		$result['column_class'] = $this->attributes['width'];
		$result['total_records'] = $this->attributes['total_records'];
		$result['view_more'] = $this->attributes['view_more'];
		$result['fields'] = $this->attributes['fields'];

		return $result;
	}

	/**
	 * Counter widget generator
	 * 
	 * @return array
	 */
	protected function counter() 
	{
		$widgetHelper = new WidgetHelper;

		$result = [];
		$result['title'] = $this->attributes['name'];
		$result['type'] = $this->attributes['type'];
		$result['model'] = $this->attributes['data_source_model'];
	
		// For aggregate function
		$aggregateFunction = $widgetHelper->getFirstValue($this->attributes['aggregate_function']);
		$result['aggregate_function'] = $aggregateFunction;
		// check if sum/avg then include aggregate_field
		if(in_array($aggregateFunction, $this->aggregateFunctionSumAvg)) {
			$result['aggregate_field'] = $widgetHelper->getLastValue($this->attributes['aggregate_function']);
		}

		if(isset($this->attributes['filter_field'])) {
			$result['filter_field'] = $this->attributes['filter_field'];
		} else {
			$result['filter_field'] = $this->defaultFilterField;
		}

		// include filter data on saving the attributes for edit purposes
		$result['filter_data'] = $this->attributes['filter_data'];
		
		// check if filter days or period
		switch ($widgetHelper->getFirstValue($this->attributes['filter_data'])) {
			case 'string':
				$result['filter_value'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
			case 'minutes':
				$result['filter_minutes'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
			case 'days':
				$result['filter_days'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
			case 'period':
				$result['filter_period'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
		}

		$result['column_class'] = $this->attributes['width'];

		$result['background_class'] = $this->attributes['bg_background'];
		$result['icon'] = $this->attributes['icon'];

        return $result;
	}

	/**
	 * Chart widget generator
	 * 
	 * @return array
	 */
	protected function chart() 
	{
		$widgetHelper = new WidgetHelper;

		$result = [];
		$result['selector'] = $widgetHelper->generateSelector($this->attributes);
		$result['title'] = $this->attributes['name'];
		$result['type'] = $this->attributes['type'];
		$result['label'] = $this->attributes['name'];
		$result['model'] = $this->attributes['data_source_model'];
		$result['group_by_field'] = $widgetHelper->getLastValue($this->attributes['group_by_column']);
		$result['group_by_period'] = $widgetHelper->getFirstValue($this->attributes['group_by_column']);
		$result['group_by_field_format'] = $this->defaultGroupByFieldFormat;
		
		// for editing this chart widget
		$result['group_by_column'] = $this->attributes['group_by_column'];

		// For aggregate function
		$aggregateFunction = $widgetHelper->getFirstValue($this->attributes['aggregate_function']);
		$result['aggregate_function'] = $aggregateFunction;
		// check if sum/avg then include aggregate_field
		if(in_array($aggregateFunction, $this->aggregateFunctionSumAvg)) {
			$result['aggregate_field'] = $widgetHelper->getLastValue($this->attributes['aggregate_function']);
		}
		
		$result['filter_field'] = $this->defaultFilterField;

		// include filter data on saving the attributes for edit purposes
		$result['filter_data'] = $this->attributes['filter_data'];
		
		switch ($widgetHelper->getFirstValue($this->attributes['filter_data'])) {
			case 'days':
				$result['filter_days'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
			case 'period':
				$result['filter_period'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
			case 'years':
				$result['filter_years'] = $widgetHelper->getLastValue($this->attributes['filter_data']);
				break;
		}

		if(isset($this->attributes['show_blank_data']) && $this->attributes['show_blank_data'] == 'yes') {
			$result['show_blank_data'] = true;
		}

		$result['column_class'] = $this->attributes['width'];

		return $result;
	}

	/**
	 * Generate code with an array to the dashoard
	 * 
	 * @return void
	 */
	public function generateCode() 
	{
		foreach($this->stubs as $file) {
			$copyTo = base_path() . $this->copyToFolder;
			$stub = base_path() . $this->stubFolder . $file;

			$this->filesystem->copy(
	            $stub, 
	            $copyTo . str_replace('.stub', '', basename($stub))
	        );

	        $files = $this->filesystem->files($copyTo, false);

	        foreach($files as $file) {
	            $this->filesystem->put($file, $this->replace(
	                $this->filesystem->get($file)
	            ));
	        }
		}
	}

	/**
	 * Dashboard widget shortcode replacement
	 */
	protected function replace($content) 
    {   
        $replacements = [
        	'$SETTINGS$' => $this->generateSettings()
        ];

        foreach($replacements as $shortcode=>$value) {
            $content = str_replace($shortcode, $value, $content);
        }
        return $content;
    }

    /**
     * Generate widget settings
     * 
     * @return string
     */
    protected function generateSettings() 
    {
    	$results = [];
    	$widgets = $this->repo->getModel()
    		->query()
    		->orderBy('ordering', 'asc')
    		->get();

    	foreach($widgets as $key=>$widget) {
    		$results[$key] = json_decode($widget->attributes, true);
    		
    		if(isset($results[$key]['type']) && in_array($results[$key]['type'], ChartType::lists())) {
    			$results[$key]['selector'] = $widget->id . '-' . $results[$key]['selector'];
    		}
    	}

    	$string = '';
    	foreach($results as $result) {
    		$string .= $this->array($result);
    	}

    	return $string;
    }

    /**
     * Array generator as string
     * 
     * @param array $items
     * @param int|1 $counter
     * @param bool $isSubArray
     * 
     * @return string
     */
    protected function array($items, $cntr = 1, $isSubArray = false) 
    {
        if($isSubArray) {
            $result = '[
';
        } else {
            $result = '        [
';
        }
        
        foreach($items as $key => $item) {
            if(!is_array($item)) {
                if($cntr == 1) {
                    $result .= $this->levelOne($key, $item);
                }

                if($cntr == 2) {
                    $result .= $this->levelTwo($key, $item);
                }

                if($cntr == 3) {
                    $result .= $this->levelThree($key, $item);
                }

                if($cntr == 4) {
                    $result .= $this->levelFour($key, $item);
                }

            } else {
                if($cntr == 1) {
                    $result .= "            '".$key."' => ".$this->array($item, ($cntr + 1), true)."";
                } elseif($cntr == 2) {
                    $result .= "                '".$key."' => ".$this->array($item, ($cntr + 1), true)."";
                } elseif($cntr == 3) {
                    $result .= "                    '".$key."' => ".$this->array($item, ($cntr + 1), true)."";
                } elseif($cntr == 4) {
                    $result .= "                        '".$key."' => ".$this->array($item, ($cntr + 1), true)."";
                }
                
            }
        }

        if($cntr == 1) {
            $result .= '        ],
';
        }

        if($cntr == 2) {
            $result .= '            ],
';
        }

        if($cntr == 3) {
            $result .= '                ],
';
        }

        if($cntr == 4) {
            $result .= '                    ],
';
        }
        

        return $result;
    }

    /**
     * Level one array generator as string
     * 
     * @param string $key
     * @param string $value
     * 
     * @return string
     */
    protected function levelOne($key, $value) 
    {
        return "            '$key' => '$value',
";
    }

    /**
     * Level two array generator as string
     * 
     * @param string $key
     * @param string $value
     * 
     * @return string
     */
    protected function levelTwo($key, $value) 
    {
        return "                '$key' => '$value',
";
    }

    /**
     * Level three array generator as string
     * 
     * @param string $key
     * @param string $value
     * 
     * @return string
     */
    protected function levelThree($key, $value) 
    {
        return "                    '$key' => '$value',
";
    }

    /**
     * Level four array generator as string
     * 
     * @param string $key
     * @param string $value
     * 
     * @return string
     */
    protected function levelFour($key, $value) 
    {
        return "                        '$key' => '$value',
";
    }

}