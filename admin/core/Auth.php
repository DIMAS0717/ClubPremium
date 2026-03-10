<?php

class Auth {

    public static function check() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
            header("Location: login.php");
            exit;
        }
    }
}