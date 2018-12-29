<?php

class Users {
    public function signup($connect, $post) {
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

    public function login($connect, $post) {
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
}

?>