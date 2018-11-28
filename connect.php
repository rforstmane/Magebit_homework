<?php
    $name = "";
    $email = "";
    $errors = array();

    $connect = mysqli_connect('localhost', 'webuser', 'secretpassword', 'magebit');

    if (isset($_POST['signup'])) {
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $password = mysqli_real_escape_string($connect, $_POST['password']);

        if(empty($name)) {
            array_push($errors, "Name is required");
        }
        if(empty($email)) {
            array_push($errors, "Email is required");
        }
        if(empty($password)) {
            array_push($errors, "Pasword is required");
        }

        if (count($errors) == 0) {
            $password = md5($password);
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password') ";
            mysqli_query($connect, $sql);
        }
    }

?>
