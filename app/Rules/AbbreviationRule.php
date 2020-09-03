<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AbbreviationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //einzelne Kostenstellen werden durch ; abgegrenzt.
        $values = explode(';', $value);
        //Stelle sicher, dass jede Kostenstelle im korrekten Format hinterlegt wird:
        foreach ($values as $v) {
            //leere Phasen werden ignoriert.
            if (empty(trim($v))) {
                continue;
            }
            //Abkürzung: Buchstaben und Zahlen, keine Leerzeichne
            if (! preg_match('#^[0-9a-zA-Z]+\s*=>[a-zA-Z0-9\s]+$#', trim($v))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Die Kostenstellen müssen im vorgegebenen Format hinterlegt werden.';
    }
}
