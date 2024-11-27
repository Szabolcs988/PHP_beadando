<?php
require_once './models/UserModel.php';

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $role = "visitor"; // alapértelmezett szerepkör
            if ($this->model->registerUser($username, $password, $role)) {
                header("Location: index.php?page=login");
            }
        }
        include './views/register.php';
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $user = $this->model->loginUser($username, $password);
            if ($user) {
                $_SESSION["user"] = $user;
                header("Location: index.php");
            } else {
                $error = "Invalid credentials!";
            }
        }
        include './views/login.php';
    }
}
?>
