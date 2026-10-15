<?php

/**
 * ApiAuth — pengelola kunci API (header X-API-KEY) untuk endpoint integrasi.
 *
 * Daftar kunci diambil dari konstanta API_KEYS (lihat app/config/config.php
 * dan app/config/config.local.php). Kunci lama tetap didukung agar integrasi
 * yang sudah berjalan (PKL) tidak rusak.
 */
class ApiAuth
{
    /**
     * Daftar kunci API yang diizinkan.
     * @return array
     */
    public static function keys()
    {
        $keys = defined('API_KEYS') ? API_KEYS : ['TUsmekisa1968'];

        if (is_string($keys)) {
            $keys = [$keys];
        }

        return array_values(array_filter((array) $keys, function ($k) {
            return $k !== null && trim((string) $k) !== '';
        }));
    }

    /**
     * Ambil kunci yang dikirim klien (header X-API-KEY).
     * @return string
     */
    public static function requestKey()
    {
        // PHP mengubah "X-API-KEY" menjadi HTTP_X_API_KEY
        foreach (['HTTP_X_API_KEY', 'HTTP_X_APIKEY', 'HTTP_API_KEY'] as $serverKey) {
            if (isset($_SERVER[$serverKey]) && trim($_SERVER[$serverKey]) !== '') {
                return trim($_SERVER[$serverKey]);
            }
        }
        return '';
    }

    /**
     * Cek apakah kunci yang diberikan termasuk kunci yang diizinkan.
     * @param string $given
     * @return bool
     */
    public static function validKey($given)
    {
        $given = trim((string) $given);
        if ($given === '') {
            return false;
        }

        foreach (self::keys() as $key) {
            if (hash_equals((string) $key, $given)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cek kunci dari header permintaan saat ini.
     * @return bool
     */
    public static function keyOk()
    {
        return self::validKey(self::requestKey());
    }

    /**
     * Hentikan permintaan dengan 401 bila kunci tidak valid.
     */
    public static function authorize()
    {
        if (!self::keyOk()) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => 'Akses Ditolak! API Key tidak valid.',
            ]);
            exit;
        }
    }
}
