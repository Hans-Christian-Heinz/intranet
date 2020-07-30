<?php


namespace App\Structs;


class ImagePlaceholder
{
    public $number;

    /**
     * ImagePlaceholder constructor.
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }
}
