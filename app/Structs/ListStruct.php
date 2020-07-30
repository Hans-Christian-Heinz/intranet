<?php


namespace App\Structs;


class ListStruct extends Struct
{
    public $type;
    public $content;

    /**
     * ListStruct constructor.
     * @param $type
     * @param $content
     */
    public function __construct(string $type, array $content)
    {
        $this->type = $type;
        $this->content = array_map('trim', $content);
    }


    public static function create(string $text)
    {
        $type = '';
        $content = [];

        //Format: ##LIST(type, (c1)(c2)(c3))##
        $text = trim(substr($text, strpos($text, '(') + 1));
        $type = trim(substr($text, 0, strpos($text, ',')));
        $text = trim(substr($text, strpos($text, ',') + 1));
        while ($text{0} == '(') {
            array_push($content, substr($text, 1, strpos($text, ')') - 1));
            $text = trim(substr($text, strpos($text, ')') + 1));
        }

        return new ListStruct($type, $content);
    }
}
