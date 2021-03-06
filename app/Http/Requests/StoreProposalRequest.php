<?php

namespace App\Http\Requests;

use App\Project;
use App\Proposal;
use App\Rules\DocumentationTableRule;
use App\Rules\PhasesRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProposalRequest extends FormRequest
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
        //$phasesRule = new PhasesRule();
        $phasesRule = new DocumentationTableRule('phases');

        $rules = [];
        $rules['start'] = 'nullable|date';
        $rules['end'] = 'nullable|date|after:start';
        foreach(Proposal::PHASES as $phase) {
            $rules[$phase['name']] = [
                'json',
                $phasesRule,
            ];
        }
        //Der Rest ist optionaler Text: es wird keine Validierung benötigt.

        return $rules;
    }
}
