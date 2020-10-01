<?php

namespace App\Rules;

use App\Berichtsheft;
use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class BerichtsheftUpdateWocheRule implements Rule
{
    /**
     * @var Berichtsheft
     */
    private $berichtsheft;

    /**
     * Create a new rule instance.
     *
     * @param Berichtsheft $berichtsheft
     */
    public function __construct(Berichtsheft $berichtsheft)
    {
        $this->berichtsheft = $berichtsheft;
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
        $user = $this->berichtsheft->owner;
        $count = $user->berichtshefte()->where('week', Carbon::create($value))->count();
        if ($count == 0) {
            return true;
        }
        else {
            if ($count == 1 && $user->berichtshefte()->where('week', Carbon::create($value))->first()->is($this->berichtsheft)) {
                return true;
            }
            else {
                return false;
            }
        }
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
