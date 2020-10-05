<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ["user_id", "company_id", "comment"];
    protected $with = ["user", "ratings"];

    // Add belongs to user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add belongs to company relationship
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function ratings()
    {
        return $this->hasMany(CategoryReview::class);
    }

    public function rating()
    {
        $ratings = $this->ratings;

        $totalStars = $ratings->reduce(function ($carry, $item) {
            return $carry + $item->stars;
        });

        return $totalStars / $ratings->count();
    }
}
