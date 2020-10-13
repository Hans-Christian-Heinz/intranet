<?php

namespace App\Http\Requests;

use App\Project;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PdfRequest extends FormRequest
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
        $rules['textart'] = [
            'required',
            Rule::in(Project::FONTS),
        ];
        $rules['textgroesse'] = 'required|int|min:4|max:15';
        //Farbe im hex-code
        $rules['textfarbe'] = 'required|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['ueberschrFarbe'] = 'required|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['kopfHintergrund'] = 'nullable|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['kopfText'] = 'nullable|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['koerperBackground'] = 'nullable|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['koerperHintergrund'] = 'nullable|regex:#^\#[0-9a-fA-F]{6}$#';
        $rules['koerperText'] = 'nullable|regex:#^\#[0-9a-fA-F]{6}$#';
        //Signatur wird ggf beim Drucken von Bewerbungsanschreiben hochgeladen
        $rules['signature'] = 'nullable|image|mimes:png|max:50';

        return $rules;
    }
}
