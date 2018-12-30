<?php

class App
{
    public $errors = [];
    public $infos = [] ;

    public function pushError($text)
    {
        array_push($this->errors, $text);
    }

    public function pushInfo($text) {
        array_push($this->infos, $text);
    }
}

?>