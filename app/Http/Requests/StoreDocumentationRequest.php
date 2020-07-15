<?php

namespace App\Http\Requests;

use App\Rules\KostenstellenRule;
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
        $kostenstellenRule = new KostenstellenRule();

        //todo
        $rules = [];
        $rules['shortTitle'] = 'nullable|string|max:30';
        $rules['longTitle'] = 'nullable|string|max:100';
        //Abschnitt Ressourcenplanung: Gehe dabon aus, dass die Abschnitte nicht umbenannt werden
        $rules['hardware'] = $kostenstellenRule;
        $rules['software'] = $kostenstellenRule;
        $rules['personal'] = $kostenstellenRule;
        //Der Rest ist optionaler Text: es wird keine Validierung benötigt.

        return $rules;
    }
}
