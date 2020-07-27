<?php

namespace App\Http\Requests;

use App\Rules\SectionExistsRule;
use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
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
        if ($this->route()->parameter('proposal')) {
            $document =$this->route()->parameter('proposal');
        }
        elseif ($this->route()->parameter('documentation')) {
            $document =$this->route()->parameter('documentation');
        }
        else {
            $document = null;
        }
        $rules = [];
        $rules['text'] = 'required|string|max:255';
        $rules['section_name'] = [
            'required',
            new SectionExistsRule($document),
        ];

        return $rules;
    }
}
