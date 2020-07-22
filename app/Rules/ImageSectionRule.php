<?php

namespace App\Rules;

use App\Section;
use App\Version;
use Illuminate\Contracts\Validation\Rule;

class ImageSectionRule implements Rule
{
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
        $section = Section::find($value);
        if (!$section || ($section->tpl != 'text_section' && $section->tpl != 'dokumentation.vgl_section')) {
            return false;
        }
        if (! $this->version->sections->contains($section)) {
            return false;
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
        return 'Dem ausgewählten Abschnitt kann kein Bild zugeordnet werden.';
    }
}
