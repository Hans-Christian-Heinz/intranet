<?php

namespace App\Http\Requests;

use App\Rules\BerichtsheftUpdateWocheRule;
use Illuminate\Foundation\Http\FormRequest;

class BerichtsheftUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'grade' => 'required|numeric|between:1,3',
            'work_activities' => 'nullable|string',
            'instructions' => 'nullable|string',
            'school' => 'nullable|string',
            'week' => [
                'required',
                'date',
                'before:today',
                new BerichtsheftUpdateWocheRule($this->route()->parameter('berichtsheft')),
            ]
        ];

        return $rules;
    }
}
