<?php

class Connection {

    public $connect;

    public function __construct() {
        $this->connect = new mysqli('localhost', 'webuser', 'secretpassword', 'magebit');
        return $this->connect;
    }

}

?>