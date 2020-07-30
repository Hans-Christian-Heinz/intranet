<?php


namespace App\Structs;


abstract class Struct
{
    public static abstract function create(string $text);
}
