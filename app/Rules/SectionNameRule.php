<?php

namespace App\Rules;

use App\Documentation;
use App\Version;
use Illuminate\Contracts\Validation\Rule;

class SectionNameRule implements Rule
{
    /**
     * @var Version
     */
    private $version;

    /**
     * Create a new rule instance.
     *
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        $this->version = $version;
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
        return $this->version->sections->where('name', $value)->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Der gewählte Name ist in diesem Dokument bereits vorhanden.';
    }
}
