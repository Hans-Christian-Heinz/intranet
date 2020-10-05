<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReviewCategory extends Component
{
    public $category;
    public $stars;
    public $message;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($category, $stars, $message)
    {
        $this->category = $category;
        $this->stars = $stars;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('bewerbungen.components.review-category');
    }
}
