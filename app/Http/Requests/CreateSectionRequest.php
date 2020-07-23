<?php

namespace App\Http\Requests;

use App\Rules\SectionNameRule;
use App\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSectionRequest extends FormRequest
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
        $project = $this->route()->parameter('project');
        $version = $project->documentation->latestVersion();
        $rules = [];
        $rules['name'] = [
            'required',
            new SectionNameRule($version),
        ];
        $rules['heading'] = [
            'required',
            'string',
        ];
        $rules['tpl'] = [
            'required',
            Rule::in(Section::TEMPLATES),
        ];
        $rules['section_id'] = [
            'required',
            'int',
            'min:0',
        ];

        return $rules;
    }
}
