<?php


namespace App\Structs;


class Phase
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $duration;

    /**
     * Phase constructor.
     * @param string $name
     * @param int $duration
     */
    public function __construct(string $name, int $duration)
    {
        $this->name = $name;
        $this->duration = $duration;
    }

    public function __toString()
    {
        return $this->name . ' : ' . $this->duration . ';';
    }

    /**
     * Beachte: der Text (Benutzereingabe) ist bereits validiert. (Valider Parameter wird vorausgesetzt.)
     *
     * @param $text
     * @return Phase
     */
    public static function create($text) {
        $values = explode(':', $text);
        return new Phase($values[0], $values[1]);
    }
}
