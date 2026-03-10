<?php

class Admin {

    public static function findByUsername($username) {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT id, username, password_hash, nombre, correo
            FROM admins
            WHERE username = ?
            LIMIT 1
        ");

        $stmt->execute([$username]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}