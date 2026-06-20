<?php

class Auth
{
    /**
     * Cek apakah pengguna memiliki role/level yang dibutuhkan.
     * @param string $required_role Nama role yang dibutuhkan (contoh: 'admin').
     * @return bool True jika memiliki role, false jika tidak.
     */
    public static function checkRole($required_role)
    {
        // Pastikan pengguna sudah login dan session 'user_roles' ada
        if (!isset($_SESSION['login_guru']) || !isset($_SESSION['user_roles'])) {
            return false;
        }

        // Cek apakah role yang dibutuhkan ada di dalam array session
        // `in_array()` akan mencari nilai di dalam sebuah array
        return in_array($required_role, $_SESSION['user_roles']);
    }
}
