<?php

namespace App\Rules;

use App\Section;
use Illuminate\Contracts\Validation\Rule;

class SectionCounterEditRule implements Rule
{
    private $section;

    /**
     * Create a new rule instance.
     *
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
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
        $parent = $this->section->getParent();
        if ($parent instanceof Section) {
            return $value == $parent->counter;
        }
        else {
            return true;
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
