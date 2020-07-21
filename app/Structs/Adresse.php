<?php


namespace App\Structs;


class Adresse
{
    public $strasse;
    public $hausnr;
    public $plz;
    public $ort;

    /**
     * Adresse constructor.
     * @param $strasse
     * @param $hausnr
     * @param $plz
     * @param $ort
     */
    public function __construct($strasse, $hausnr, $plz, $ort)
    {
        $this->strasse = $strasse;
        $this->hausnr = $hausnr;
        $this->plz = $plz;
        $this->ort = $ort;
    }

    public function __toString()
    {
        return $this->strasse . ' ' . $this->hausnr . "\n" . $this->plz . ' ' . $this->ort;
    }
}
