<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentationRequest extends FormRequest
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
        //todo
        $rules = [];
        $rules['shorTitle'] = 'nullable|string|max:30';
        $rules['longTitle'] = 'nullable|string|max:100';

        return $rules;
    }
}
