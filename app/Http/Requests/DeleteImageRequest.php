<?php

namespace App\Http\Requests;

use App\Rules\DeleteImageRule;
use App\Rules\ImageExistsRule;
use App\Rules\OwnsImageRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteImageRequest extends FormRequest
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
        $rules['datei'] = [
            'required',
            new ImageExistsRule(),
            new OwnsImageRule(app()->user),
        ];

        return $rules;
    }
}
