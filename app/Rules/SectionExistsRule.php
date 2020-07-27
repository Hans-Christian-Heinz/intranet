<?php

namespace App\Rules;

use App\Documentation;
use App\Proposal;
use Illuminate\Contracts\Validation\Rule;

class SectionExistsRule implements Rule
{
    private $document;

    /**
     * Create a new rule instance.
     *
     * @param $document
     */
    public function __construct($document = null)
    {
        $this->document = $document;
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
        if (is_null($this->document)) {
            return false;
        }
        $version = $this->document->latestVersion();
        return $version->sections->where('name', $value)->isNotEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Es existiert kein Abschnitt des angegebenen Namens.';
    }
}
