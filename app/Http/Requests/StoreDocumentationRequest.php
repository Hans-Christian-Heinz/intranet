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
        //Soll-Ist-Vergleich: tatsächlich verwendete Zeit
        $rules['planung'] = 'int|min:0';
        $rules['entwurf'] = 'int|min:0';
        $rules['implementierung'] = 'int|min:0';
        $rules['test'] = 'int|min:0';
        $rules['abnahme'] = 'int|min:0';
        //Der Rest ist optionaler Text: es wird keine Validierung benötigt.

        return $rules;
    }
}
