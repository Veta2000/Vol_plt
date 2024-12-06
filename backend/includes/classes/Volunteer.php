<?php

require_once 'AbstractUser.php';

class Volunteer extends AbstractUser {
    public function getProfilePage() {
        return 'profile/volunteer.php';
    }
}
