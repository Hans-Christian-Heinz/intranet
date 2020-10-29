<?php

namespace App\Rules;

use App\Section;
use Illuminate\Contracts\Validation\Rule;

/**
 * Klasse wird nicht mehr verwendet
 *
 * Class PhasesRule
 * @package App\Rules
 */
class PhasesRule implements Rule
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
        //alte Version, nicht mehr gültig.
        //einzelne Phasen werden durch ; abgegrenzt.
        $values = explode(';', $value);
        //Stelle sicher, dass jede Phase im korrekten Format hinterlegt wird:
        foreach ($values as $v) {
            //leere Phasen werden ignoriert.
            if (empty(trim($v))) {
                continue;
            }
            //Überprüfe: Phasenname : Dauer
            $phase = explode(':', $v);
            if (count($phase) != 2 || empty(trim($phase[0])) || !preg_match("#^[0-9]+$#", trim($phase[1]))) {
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
        return 'Die Phasen müssen im vorgegebenen Format hinterlegt werden.';
    }
}
