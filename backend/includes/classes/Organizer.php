<?php

require_once 'AbstractUser.php';

class Organizer extends AbstractUser {
    public function getProfilePage() {
        return 'profile/organizer.php';
    }
}
