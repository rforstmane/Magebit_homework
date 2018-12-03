<?php
session_start();
$name = "";
$email = "";
$errors = array();

$connect = mysqli_connect('localhost', 'webuser', 'secretpassword', 'magebit');

if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    if (empty($name)) {
        array_push($errors, "Name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    $user_check = "SELECT * FROM users WHERE email='$email' LIMIT 1 ";
    $results = mysqli_query($connect, $user_check);
    $user = mysqli_fetch_assoc($results);

    if ($user) {
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }


    if (count($errors) == 0) {
        $password = md5($password);
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password') ";
        mysqli_query($connect, $sql);
        $_SESSION['name'] = $name;
        $_SESSION['success'] = "You are now logged in";
        header('location: logged_in.php');
    }
}

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    if (empty($email)) {
        $_SESSION['email_error'] = "Email is required";
        $errors++;
    }
    if (empty($password)) {
//        array_push($errors, "Password is required");
        $_SESSION['password_error'] = "Password is required";
        $errors++;
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT id, name FROM users WHERE email='$email' AND password='$password' LIMIT 1 ";
        $result = mysqli_query($connect, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $id = $row['id'];
            $_SESSION['name'] = $row['name'];
//            $_SESSION['success'] = "You are now logged in";
            header('location: logged_in.php');
        } else {
            array_push($errors, "Wrong email or password");

        }
    }

}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['name']);
    header('location: main.php');
}

?>
