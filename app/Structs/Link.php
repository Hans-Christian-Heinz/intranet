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
     * @return Link
     */
    public static function create(string $text) {
        $text = substr($text, strpos($text, '(') + 1);
        $ziel = substr($text, 0, strpos($text, ','));
        $text = substr($text, strpos($text, ',') + 2);
        $text = substr($text, 0, strpos($text, ')'));

        return new Link($ziel, $text);
    }
}
