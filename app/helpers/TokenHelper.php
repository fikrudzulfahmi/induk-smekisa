<?php

class TokenHelper
{
    // Kunci rahasia untuk enkripsi (Bisa Anda ganti dengan kalimat acak yang sulit ditebak)
    private static $secret_key = 'SMEKISA68';
    private static $encrypt_method = "AES-256-CBC";

    /**
     * Membuat Token yang Aman untuk URL
     */
    public static function generateToken($id)
    {
        $key = hash('sha256', self::$secret_key);
        // Menggunakan 16 karakter pertama dari hash sebagai Initialization Vector (IV)
        $iv = substr(hash('sha256', self::$secret_key), 0, 16);

        // Enkripsi ID
        $output = openssl_encrypt($id, self::$encrypt_method, $key, 0, $iv);

        // Encode dengan Base64, lalu buat URL-Safe dengan me-replace karakter bermasalah
        $base64 = base64_encode($output);
        $url_safe_token = str_replace(['+', '/', '='], ['-', '_', ''], $base64);

        return $url_safe_token;
    }

    /**
     * Memvalidasi dan Mendeskripsi Token dari URL
     */
    public static function validateToken($token)
    {
        // Kembalikan karakter URL-Safe ke format Base64 standar
        $base64 = str_replace(['-', '_'], ['+', '/'], $token);

        // Kembalikan padding '=' yang sebelumnya dibuang
        $padding = strlen($base64) % 4;
        if ($padding > 0) {
            $base64 .= str_repeat('=', 4 - $padding);
        }

        $key = hash('sha256', self::$secret_key);
        $iv = substr(hash('sha256', self::$secret_key), 0, 16);

        // Deskripsi token
        $decrypted_id = openssl_decrypt(base64_decode($base64), self::$encrypt_method, $key, 0, $iv);

        // Jika gagal deskripsi (misal token asal-asalan), return false
        if (!$decrypted_id) {
            return false;
        }

        // Kembalikan ID aslinya (format integer)
        return (int)$decrypted_id;
    }
}
