<?php

class User {
    private $name;
    private $email;
    private $mobile;
    private $position;
    private $password;

    public function __construct($name, $email, $mobile, $position, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->position = $position;
        $this->password = $password;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function getPosition() {
        return $this->position;
    }

    public function getPassword() {
        return $this->password;
    }
}

class UserFactory {
    public static function create($name, $email, $mobile, $position, $password) {
        // Hash the password before creating the user object
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return new User($name, $email, $mobile, $position, $hashedPassword);
    }
}
