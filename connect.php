<?php

require_once('Users.php');
require_once('App.php');
require_once('Attributes.php');
require_once('Connection.php');

$instance = new App;
$user = new Users($instance);
$attribute = new Attributes($instance);
$connection = new Connection;

session_start();

function dumpAndDie($arg)
{
    echo "<pre>";
    var_dump($arg);
    die();
}

if (isset($_POST['signup'])) {
    $user->signup($_POST);
}

if (isset($_POST['login']) && !empty($_POST['login'])) {
    $user->login($_POST);
}

if (isset($_GET['logout'])) {
    $user->logout();
}

if (isset($_POST['submit'])) {
    $attribute->submit($_POST);
}

$connection->__construct();
?>
