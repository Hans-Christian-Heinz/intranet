<?php


namespace App\Structs;


class Table extends Struct
{
    public $rows;
    public $footer;

    /**
     * Table constructor.
     * @param array $rows
     * @param string $footer
     */
    public function __construct(array $rows, string $footer)
    {
        $this->rows = $rows;
        $this->footer = $footer;
    }

    public static function create(string $text)
    {
        //Die Methode wird in der Methode formatText($text) der Klasse Section aufgerufen
        //An der Stelle ist fürdas Format des Parameters validiert: Beginnt mit "##TABLE(" und endet mit ')##'
        $rows = [];
        $footer = '';

        //Format: ##TABLE({header}[row])(fußzeile)##
        //Alles, was nicht in den korrekten Klammern steht, wird ignoriert.
        $text = trim(substr($text, strpos($text, '(') + 1));
        while ($text{0} !== ')') {
            switch ($text{0}) {
                case '{':
                    $end = strpos($text, '}');
                    if ($end === false) {
                        $text = trim(substr($text, 1));
                        continue;
                    }
                    $help = substr($text, 1, $end - 1);
                    array_push($rows, new TableRow(explode('||', $help), true));
                    $text = trim(substr($text, $end + 1));
                    break;
                case '[':
                    $end = strpos($text, ']');
                    if ($end === false) {
                        $text = trim(substr($text, 1));
                        continue;
                    }
                    $help = substr($text, 1, $end - 1);
                    array_push($rows, new TableRow(explode('||', $help)));
                    $text = trim(substr($text, $end + 1));
                    break;
                default:
                    $text = trim(substr($text, 1));
            }
        }
        $text = trim(substr($text, 1));
        if ($text{0} == '(') {
            $end = strpos($text, ')');
            if ($end !== false) {
                $footer = substr($text, 1, strpos($text, ')') - 1);
            }
        }

        return new Table($rows, $footer);
    }
}
