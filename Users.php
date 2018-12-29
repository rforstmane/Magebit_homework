<?php

require_once ('Config.php');

class Users {
    private $dbConnection;

    function __construct()
    {
        $this->dbConnection  = new mysqli(Config::HOST, Config::USER, Config::PASSWORD, Config::DATABASE);
    }

    function __destruct()
    {
        $this->dbConnection->close();
    }

    public function signup($post) {
        $name = $this->dbConnection->escape_string($post['name']);
        $email = $this->dbConnection->escape_string($post['email']);
        $password = $this->dbConnection->escape_string($post['password']);

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
        $results = $this->dbConnection->query($user_check);
        $user = $results->fetch_assoc();

        if ($user) {
            if ($user['email'] === $email) {
                array_push($errors, "Email already exists");
            }
        }

        if (count($errors) == 0) {
            $password = md5($password);
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password') ";
            if ($this->dbConnection->query($sql)) {
                $_SESSION['name'] = $name;
                $_SESSION['user_id'] = $this->dbConnection->insert_id;
                $_SESSION['success'] = "You are now logged in";
                header('location: logged_in');
            }
        }
    }

    public function login($post) {
        $email = $this->dbConnection->escape_string( $post['email']);
        $password = $this->dbConnection->escape_string($post['password']);

        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT id, name FROM users WHERE email='$email' AND password='$password' LIMIT 1 ";
            $result = $this->dbConnection->query($query);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $id;
                header('location: logged_in');
            } else {
                array_push($errors, "Wrong email or password");
            }
        }
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['name']);
        header('location: main');
    }
}

?>