<?php

/**
 * Class App returns pushErrors and pushInfo comments
 */
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