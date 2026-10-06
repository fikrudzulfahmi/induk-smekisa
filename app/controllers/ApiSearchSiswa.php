<?php

/**
 * Alias route akar untuk pencarian siswa.
 * GET /apiSearchSiswa?q=<nama atau NIS>&tingkat=<opsional>
 *
 * Meneruskan ke Siswa::apiSearchSiswa() supaya jalur
 * /Siswa/apiSearchSiswa dan /apiSearchSiswa sama-sama bekerja.
 */
require_once __DIR__ . '/Siswa.php';

class ApiSearchSiswa extends Controller
{
    public function index()
    {
        $siswa = new Siswa();
        $siswa->apiSearchSiswa();
    }
}
