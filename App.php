<?php

class App
{
    public $errors = [];

    public function pushError($text) {
        array_push($this->errors, $text);
    }
}

?>