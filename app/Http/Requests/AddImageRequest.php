<?php

namespace App\Http\Requests;

use App\Rules\ImageExistsRule;
use App\Rules\ImageSectionRule;
use App\Rules\OwnsImageRule;
use Illuminate\Foundation\Http\FormRequest;

class AddImageRequest extends FormRequest
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

        $rules['footnote'] = [
            'nullable',
            'string',
            'max:255',
        ];
        $rules['path'] = [
            'required',
            new ImageExistsRule(),
            new OwnsImageRule($project->user),
        ];
        $rules['section_id'] = [
            'required',
            'int',
            'min:1',
            new ImageSectionRule($version),
        ];

        return $rules;
    }
}
