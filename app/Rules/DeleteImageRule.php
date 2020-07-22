<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class DeleteImageRule implements Rule
{
    private $username;

    /**
     * Create a new rule instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->username = $user->ldap_username;
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
        if (strpos($value, 'images/' . $this->username) !== 0) {
            return false;
        }
        if (Storage::disk('public')->exists($value)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Sie können nur Bilder, die existieren und Ihrem Benutzerprofil zugeordnet sind, löschen.';
    }
}
