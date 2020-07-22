<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class OwnsImageRule implements Rule
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
        return 'Die ausgewählte Bilddatei ist einem anderen Benutzer zugeordnet.';
    }
}
