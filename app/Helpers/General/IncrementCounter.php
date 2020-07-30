<?php


namespace App\Helpers\General;


class IncrementCounter
{
    private $nr;

    /**
     * IncrementCounter constructor.
     * @param $nr
     */
    public function __construct($nr = 0)
    {
        $this->nr = $nr;
    }

    public function nextNumber() {
        $this->nr++;
        return $this->nr;
    }

    public function getNumber() {
        return $this->nr;
    }
}
