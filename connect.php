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

function signup($connect, $post) {
    $name = mysqli_real_escape_string($connect, $post['name']);
    $email = mysqli_real_escape_string($connect, $post['email']);
    $password = mysqli_real_escape_string($connect, $post['password']);

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
        if (mysqli_query($connect, $sql)) {
            $_SESSION['name'] = $name;
            $_SESSION['user_id'] = mysqli_insert_id($connect);
            $_SESSION['success'] = "You are now logged in";
            header('location: logged_in');
        }
    }
}

if (isset($_POST['signup'])) {
    signup($connect, $_POST);
}

function login($connect, $post) {
    $email = mysqli_real_escape_string($connect, $post['email']);
    $password = mysqli_real_escape_string($connect, $post['password']);

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
            header('location: logged_in');
        } else {
            array_push($errors, "Wrong email or password");
        }
    }
}

if (isset($_POST['login']) && !empty($_POST['login'])) {
   login($connect, $_POST);
}



if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['name']);
    header('location: main');
}

function merge_form_input($id_array, $keey_array, $value_array, $db_connect)
{
    $all_inputs_array = [];
    foreach ($id_array as $i => $item_id) {
        $object = new stdClass();
        $object->id = mysqli_real_escape_string($db_connect, $id_array[$i]);
        $object->key = mysqli_real_escape_string($db_connect, $keey_array[$i]);
        $object->value = mysqli_real_escape_string($db_connect, $value_array[$i]);
        array_push($all_inputs_array, $object);
    }
    return $all_inputs_array;
}

function store_new_inputs($inputs_array, $user_id, $db_connect)
{
    $new_inputs = [];
    foreach ($inputs_array as $item) {
        if ($item->id == "") {
            array_push($new_inputs, $item);
        }
    }

    foreach ($new_inputs as $input) {
        if (!(empty($input->key) && empty($input->value))) {
            $mysql = "INSERT INTO attributes (user_id, keey, value) VALUES ('$user_id', '$input->key', '$input->value') ";
            mysqli_query($db_connect, $mysql);
        }
    }
}

function get_potential_updated($inputs_array)
{
    $potential_updated_inputs = [];
    foreach ($inputs_array as $item) {
        if ($item->id != "") {
            array_push($potential_updated_inputs, $item);
        }
    }
    return $potential_updated_inputs;
}

function join_ids($potential_updated_inputs)
{
    $id_array = [];
    foreach ($potential_updated_inputs as $item) {
        array_push($id_array, $item->id);
    }

    $result = join(", ", $id_array);
    return $result;
}

function get_old_inputs($potential_updated_inputs, $user_id, $db_connect)
{
    $old_inputs_query = "SELECT * FROM attributes WHERE user_id='$user_id' AND id IN (" . join_ids($potential_updated_inputs) . ") ";
    $old_inputs_result = mysqli_query($db_connect, $old_inputs_query);
    if ($old_inputs_result != false) {
        $old_inputs_rows = mysqli_fetch_all($old_inputs_result, MYSQLI_ASSOC);
        foreach ($old_inputs_rows as $i => $row) {
            $old_inputs_rows[$i] = (object)$row;
        }
        return $old_inputs_rows;
    } else {
        return [];
    }
}

function get_all_attributes($user_id, $db_connect)
{
    $old_inputs_query = "SELECT * FROM attributes WHERE user_id ='$user_id' ";
    $old_inputs_result = mysqli_query($db_connect, $old_inputs_query);
    $old_inputs_rows = mysqli_fetch_all($old_inputs_result, MYSQLI_ASSOC);

    foreach ($old_inputs_rows as $i => $row) {
        $old_inputs_rows[$i] = (object)$row;
    }
    return $old_inputs_rows;
}

function find_input_by_id($id, $searchable_array)
{
    foreach ($searchable_array as $item) {
        if ($item->id == $id) {
            return $item;
        }
    }
    return null;
}

function update_existing_inputs($inputs_array, $user_id, $db_connect)
{
    $potential_updated_inputs = get_potential_updated($inputs_array);
    $old_inputs = get_old_inputs($potential_updated_inputs, $user_id, $db_connect);

    foreach ($potential_updated_inputs as $potential_version) {
        $old_version = find_input_by_id($potential_version->id, $old_inputs);

        if ($old_version != NULL) {
            if ($old_version->keey != $potential_version->key || $old_version->value != $potential_version->value) {
                $update_inputs_query = "UPDATE attributes SET keey ='$potential_version->key', value = '$potential_version->value' WHERE id='$potential_version->id'";
                mysqli_query($db_connect, $update_inputs_query);
            }
        }
    }
}

function delete_removed_inputs($inputs_array, $user_id, $db_connect)
{
    $potential_updated_inputs = get_potential_updated($inputs_array);
    $old_inputs = get_all_attributes($user_id, $db_connect);

    foreach ($old_inputs as $old_version) {
        $potential_version = find_input_by_id($old_version->id, $potential_updated_inputs);
        if ($potential_version == NULL) {
            $deleted_inputs_query = "DELETE FROM attributes WHERE id='$old_version->id' AND user_id = '$user_id'  ";
            mysqli_query($db_connect, $deleted_inputs_query);
        }
    }
}

if (isset($_POST['submit'])) {
    // lokals mainigais, lai vieglak stradat
    $user_id = $_SESSION['user_id'];
    // all inputs submitted form
    $all_inputs_array = merge_form_input($_POST['id'], $_POST['keey'], $_POST['value'], $connect);
    // inputs which have been newly added
    delete_removed_inputs($all_inputs_array, $user_id, $connect);
    store_new_inputs($all_inputs_array, $user_id, $connect);
    update_existing_inputs($all_inputs_array, $user_id, $connect);
    array_push($errors, "Changes saved successfully");
}
?>
