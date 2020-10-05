<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryReview extends Model
{
    protected $table = "category_review";

    protected $fillable = ["comment", "stars", "category_id"];

    protected $with = ["category"];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
