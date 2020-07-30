<?php


namespace App\Structs;


class TableRow
{
    public $content;
    public $isHeader;

    /**
     * TableCell constructor.
     * @param array $content
     * @param boolean $isHeader
     */
    public function __construct(array $content, $isHeader = false)
    {
        $this->content = array_map('trim', $content);
        $this->isHeader = $isHeader;
    }
}
