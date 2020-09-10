<?php

namespace App\Http\Requests;

use App\Rules\SectionCounterEditRule;
use App\Rules\SectionNameRule;
use App\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditSectionRequest extends FormRequest
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
        $section = $this->route()->parameter('section');
        $version = $project->documentation->latestVersion();
        $rules = [];
        $rules['name'] = [
            'required',
            new SectionNameRule($version, $section->name),
        ];
        $rules['heading'] = [
            'required',
            'string',
        ];
        $rules['tpl'] = [
            'required',
            Rule::in(Section::TEMPLATES),
        ];
        $rules['sequence'] = [
            'required',
            'int',
            'min:0',
            'max:' . ($section->getParent()->getSections($version)->count() - 1),
        ];
        $rules['counter'] = [
            'required',
            Rule::in(['none', 'inhalt', 'anhang']),
            new SectionCounterEditRule($section),
        ];

        return $rules;
    }
}
