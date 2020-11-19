<?php

namespace App\Http\Requests;

use App\Rules\IsAdminRule;
use Illuminate\Foundation\Http\FormRequest;

class ChooseSupervisorRequest extends FormRequest
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
        $rules = [];
        $rules['supervisor_id'] = [
            'required',
            'int',
            'min:0',
            new IsAdminRule(),
        ];

        return $rules;
    }
}
