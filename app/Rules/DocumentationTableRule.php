<?php

namespace App\Rules;

use App\Section;
use Illuminate\Contracts\Validation\Rule;

class DocumentationTableRule implements Rule
{
    private $tpls;
    private $message;

    /**
     * Create a new rule instance.
     *
     * @param $key
     */
    public function __construct($key)
    {
        $this->tpls = Section::TABLETPLS[$key];
        $this->message = 'Das Format der Tabelle muss eingehalten werden.';
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
        $valid = true;
        $val = json_decode($value, true);
        foreach ($val as $v) {
            foreach($this->tpls as $tpl) {
                if ($tpl['required']) {
                    $valid = $valid && isset($v[$tpl['name']]) && ($v[$tpl['name']] || $v[$tpl['name']] === 0 || $v[$tpl['name']] === '0');
                    if (! $valid) {
                        $this->message = 'Der Wert ' . $tpl['name'] . ' muss in jeder Zeile vorhanden sein.';
                        return false;
                    }
                }
                if (isset($v[$tpl['name']])) {
                    switch ($tpl['type']) {
                        case 'text':
                            break;
                        case 'number':
                            //ctype_digit: positive ganze Zahlen und 0
                            //ltrim: left trim
                            if (isset($tpl['step']) && ctype_digit(ltrim($tpl['step']))) {
                                $valid = $valid && ctype_digit(ltrim($v[$tpl['name']], ' -'));
                                if (!$valid) {
                                    $this->message = 'Der Wert ' . $tpl['name'] . ' muss in jeder Zeile ganzzahlig sein';
                                    return false;
                                }
                            }
                            else {
                                $valid = $valid && is_numeric($v[$tpl['name']]);
                                if (!$valid) {
                                    $this->message = 'Der Wert ' . $tpl['name'] . ' muss in jeder Zeile numerisch sein';
                                    return false;
                                }
                            }
                            if (isset($tpl['min'])) {
                                $valid = $valid && $v[$tpl['name']] >= $tpl['min'];
                                if (!$valid) {
                                    $this->message = 'Der Wert ' . $tpl['name'] . ' muss in jeder Zeile mindestens ' . $tpl['min'] . ' sein';
                                    return false;
                                }
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
        return $this->message;
    }
}
