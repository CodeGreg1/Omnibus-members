<?php

namespace Modules\DashboardWidgets\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Base\Support\Widgets\ChartType;

class AdminStoreDashboardWidgetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',            
            'type' => 'required|max:25',
            'data_source_model' => 'required'
        ];

        if(in_array(request('type'), ChartType::lists())) {
            $rules['group_by_column'] = 'required';
        }

        if(in_array(request('type'), array_merge(ChartType::lists(), ['counter']))) {
            $rules['aggregate_function'] = 'required';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('admin.dashboard-widgets.store');
    }
}
