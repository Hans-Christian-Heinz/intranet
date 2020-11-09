<?php

namespace App\Http\Requests;

use App\Rules\AbbreviationRule;
use App\Rules\DocumentationTableRule;
use App\Rules\KostenstellenRule;
use App\Rules\SectionTextComponentRule;
use App\Version;
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
        $project = $this->route()->parameter('project');
        $version = $project->documentation->latestVersion();
        $sectionNames = array_map(function($s) {
            return $s->name;
        }, Version::sectionNameHeadings($version->id));

        //$kostenstellenRule = new KostenstellenRule();
        $kostenstellenRule = new DocumentationTableRule('kostenstellen');
        $stcRule = new SectionTextComponentRule($sectionNames, $project->user);

        $rules = [];
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
        //Abkürzungsverzeichnis
        $rules['abbreviations'] = [
            'json',
            //new AbbreviationRule(),
            new DocumentationTableRule('abbr'),
        ];
        //vue Komponente section-text: versteckter input: $name . '_is_stc'
        foreach (array_keys($this->all()) as  $name) {
            $isStc = $name . '_is_stc';
            if ($this->$isStc) {
                $rules[$name] = [
                    'json',
                    $stcRule,
                ];
            }
        }

        //Der Rest ist optionaler Text: es wird keine Validierung benötigt.

        return $rules;
    }
}
