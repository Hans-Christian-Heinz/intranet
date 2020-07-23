<?php

namespace App\Rules;

use App\Documentation;
use App\Section;
use App\Version;
use Illuminate\Contracts\Validation\Rule;

class SectionNameRule implements Rule
{
    /**
     * @var Version
     */
    private $version;
    /**
     * Wenn ein Abschnitt bearbeitet wird, ist der eigene Name die Ausnahme.
     * @var string|null
     */
    private $exception;

    /**
     * Create a new rule instance.
     *
     * @param Version $version
     * @param string|null $exception
     */
    public function __construct(Version $version, $exception = null)
    {
        $this->version = $version;
        $this->exception = $exception;
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
        if ($this->exception == $value) {
            return true;
        }
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
