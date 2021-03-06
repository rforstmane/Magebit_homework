<?php

require_once('Config.php');

/**
 * Class Users allows login and create new user
 */
class Users
{
    private $dbConnection;
    private $app;
    public $name;
    public $email;
    public $password;

    public function __construct($instance)
    {
        $this->name = "";
        $this->email = "";
        $this->app = &$instance;
        $this->dbConnection = new mysqli(Config::HOST, Config::USER, Config::PASSWORD, Config::DATABASE);
    }

    public function __destruct()
    {
        $this->dbConnection->close();
    }

    /**
     * this function post request goes through all inputs fields, if there is some empty fields or similar email from database, appears error,
     * if there is no errors, creates new user and new user can log in and redirect to logged_in page
     * @param $post
     */
    public function signup($post)
    {
        $this->name = $this->dbConnection->escape_string($post['name']);
        $this->email = $this->dbConnection->escape_string($post['email']);
        $this->password = $this->dbConnection->escape_string($post['password']);

        if (empty($this->name)) {
            $this->app->pushError("Name is required");
        }
        if (empty($this->email)) {
            $this->app->pushError("Email is required");
        }
        if (empty($this->password)) {
            $this->app->pushError("Password is required");
        }

        $user_check = "SELECT * FROM users WHERE email='$this->email' LIMIT 1 ";
        $results = $this->dbConnection->query($user_check);
        $user = $results->fetch_assoc();

        if ($user) {
            if ($user['email'] === $this->email) {
                $this->app->pushError("Email already exists");
            }
        }

        if (count($this->app->errors) == 0) {
            $this->password = md5($this->password);
            $sql = "INSERT INTO users (name, email, password) VALUES ('$this->name', '$this->email', '$this->password') ";
            if ($this->dbConnection->query($sql)) {
                $_SESSION['name'] = $this->name;
                $_SESSION['user_id'] = $this->dbConnection->insert_id;
                header('location: logged_in');
            }
        }
    }

    /**
     * this function post request goes through all inputs fields, if there is some empty fields, appears error,
     * if there is no errors, user can log in and redirect to logged_in page
     * @param $post
     */
    public function login($post)
    {
        $this->email = $this->dbConnection->escape_string($post['email']);
        $this->password = $this->dbConnection->escape_string($post['password']);

        if (empty($this->email)) {
            $this->app->pushError("Email is required");
        }
        if (empty($this->password)) {
            $this->app->pushError("Password is required");
        }

        if (count($this->app->errors) == 0) {
            $this->password = md5($this->password);
            $query = "SELECT id, name FROM users WHERE email='$this->email' AND password='$this->password' LIMIT 1 ";
            $result = $this->dbConnection->query($query);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $id;
                header('location: logged_in');
            } else {
                $this->app->pushError("Wrong email or password");
            }
        }
    }

    /**
     * in this function, when user clicks 'sign out', session destroy an user can log out from account
     */
    public function logout()
    {
        session_destroy();
        unset($_SESSION['name']);
        header('location: main');
    }
}

?>