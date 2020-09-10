<?php

namespace App\Rules;

use \App\Section;
use Illuminate\Contracts\Validation\Rule;

class SectionCounterRule implements Rule
{
    private $section_id;

    /**
     * Create a new rule instance.
     *
     * @param $section_id
     */
    public function __construct($section_id)
    {
        $this->section_id = $section_id;
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
        if ($this->section_id == 0) {
            return true;
        }
        else {
            $section = Section::find($this->section_id);
            return (! is_null($section) && $section->counter == $value);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Die Nummerierung eines Unterabschnitts muss mit der Nummerierung eines Überabschnitts übereinstimmen.';
    }
}
