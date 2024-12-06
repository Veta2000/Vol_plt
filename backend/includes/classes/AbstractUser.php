<?php

abstract class AbstractUser {
    protected $name;
    protected $email;
    protected $role;

    public function __construct($name, $email, $role) {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public function getName() {
        return $this->name;
    }

    public function getRole() {
        return $this->role;
    }

    abstract public function getProfilePage();
}
