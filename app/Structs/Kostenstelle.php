<?php


namespace App\Structs;


class Kostenstelle
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var float
     */
    public $prize;

    /**
     * Kostenstelle constructor.
     * @param string $name
     * @param string $description
     * @param float $prize
     */
    public function __construct(string $name, string $description, float $prize)
    {
        $this->name = $name;
        $this->description = $description;
        $this->prize = $prize;
    }

    public function __toString()
    {
        return $this->name . ' : ' . $this->description . ' : ' . $this->prize . ';';
    }

    /**
     * Beachte: der Text (Benutzereingabe) ist bereits validiert. (Valider Parameter wird vorausgesetzt.)
     *
     * @param $text
     * @return Kostenstelle
     */
    public static function create($text) {
        $values = explode(':', $text);
        $prize = floatval(str_replace(',', '.', $values[2]));
        return new Kostenstelle($values[0], $values[1], $prize);
    }
}
