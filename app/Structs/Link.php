<?php


namespace App\Structs;


class Link extends Struct
{
    public $ziel;
    public $text;

    /**
     * Link constructor.
     * @param $ziel
     * @param $text
     */
    public function __construct($ziel, $text)
    {
        $this->ziel = $ziel;
        $this->text = $text;
    }

    /**
     * @param string $text Format: ##LINK($ziel, $text)##
     * @return Link|string
     */
    public static function create(string $text) {
        //Bei Methodenaufruf ist die Eingabe insofern validiert, dass sie mit "##LINK(" beginnt und mit ")##" endet.

        $text = substr($text, strpos($text, '(') + 1);
        $delimiter = strpos($text, ',');
        if ($delimiter !== false) {
            $ziel = trim(substr($text, 0, strpos($text, ',')));
            $text = trim(substr($text, strpos($text, ',') + 1));
            $text = trim(substr($text, 0, strpos($text, ')')));

            return new Link($ziel, $text);
        }
        else {
            return '';
        }
    }
}
