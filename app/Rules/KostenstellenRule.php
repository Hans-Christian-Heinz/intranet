<?php

namespace App\Rules;

use App\Section;
use Illuminate\Contracts\Validation\Rule;

class KostenstellenRule implements Rule
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
        /*//einzelne Kostenstellen werden durch ; abgegrenzt.
        $values = explode(';', $value);
        //Stelle sicher, dass jede Kostenstelle im korrekten Format hinterlegt wird:
        foreach ($values as $v) {
            //leere Phasen werden ignoriert.
            if (empty(trim($v))) {
                continue;
            }
            //Überprüfe: Kostenstelle : Beschreibung : Kosten
            $phase = explode(':', $v);
            //RegExp: Ziffernfolge (mindestens 1 Ziffer + , oder . + Ziffernfolge (0 bis 2 Ziffern)
            if (count($phase) != 3 || empty(trim($phase[0])) || !preg_match("#^[0-9]+[,.]?[0-9]{0,2}$#", trim($phase[2]))) {
                return false;
            }
        }

        return true;*/

        $valid = true;
        $tpls = Section::TABLETPLS['kostenstellen'];
        $val = json_decode($value, true);
        foreach ($val as $v) {
            foreach($tpls as $tpl) {
                if ($tpl['required']) {
                    $valid = $valid && isset($v[$tpl['name']]);
                }
                if (isset($val[$tpl['name']])) {
                    switch ($tpl['type']) {
                        case 'text':
                            break;
                        case 'number':
                            if (isset($tpl['step']) && is_int($tpl['step'])) {
                                $valid = $valid && is_int($v[$tpl['name']]);
                            }
                            else {
                                $valid = $valid && is_numeric($v[$tpl['name']]);
                            }
                            break;
                    }
                }
            }
        }

        return $valid;
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
