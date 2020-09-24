<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class BerichtsHeftWocheRule implements Rule
{
    private $user;

    /**
     * Create a new rule instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        //Berichtsheft ist nur dann anlegbar, wenn für die ausgewähltze Woche und den Benutzer noch kein Berichtsheft vorliegt.
        return $this->user->berichtshefte()->where('week', Carbon::create($value))->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Für die ausgewählte Woche liegt bereits ein Berichtsheft vor.';
    }
}
