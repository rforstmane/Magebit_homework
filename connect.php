<?php
session_start();
$name = "";
$email = "";
$errors = array();

$connect = mysqli_connect('localhost', 'webuser', 'secretpassword', 'magebit');
function dump_and_die($arg)
{
    echo "<pre>";
    var_dump($arg);
    die();
}

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

if (isset($_POST['login']) && !empty($_POST['login'])) {
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $password = mysqli_real_escape_string($connect, $_POST['password']);

    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT id, name FROM users WHERE email='$email' AND password='$password' LIMIT 1 ";
        $result = mysqli_query($connect, $query);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result);
            $id = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $id;
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

if (isset($_POST['submit'])) {
    $id_array = $_POST['id'];
    $keey_array = $_POST['keey'];
    $value_array = $_POST['value'];
    // all inputs submitted form
    $all_inputs_array = [];
    $user_id = $_SESSION['user_id'];
    // inputs which have been newly added
    $new_inputs = [];

    foreach ($id_array as $i => $item_id) {
        $object = new stdClass();
        $object->id = mysqli_real_escape_string($connect, $id_array[$i]);
        $object->key = mysqli_real_escape_string($connect, $keey_array[$i]);
        $object->value = mysqli_real_escape_string($connect, $value_array[$i]);
        array_push($all_inputs_array, $object);
    }

    foreach ($all_inputs_array as $item) {
        if ($item->id == "") {
            array_push($new_inputs, $item);
        }
    }

    foreach ($new_inputs as $input) {
        if (!(empty($input->key) && empty($input->value))) {
            $mysql = "INSERT INTO attributes (user_id, keey, value) VALUES ('$user_id', '$input->key', '$input->value') ";
            mysqli_query($connect, $mysql);
        }
    }
}
?>
